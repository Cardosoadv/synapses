
# Automated conflict resolution script for all remaining PR branches
# Resolves the same 5 files that always conflict

param(
    [string]$BranchName
)

function Resolve-Branch {
    param([string]$branch)

    Write-Host "`n=== Processing: $branch ===" -ForegroundColor Cyan
    
    # Checkout branch
    git checkout $branch 2>&1 | Out-Null
    if ($LASTEXITCODE -ne 0) {
        Write-Host "ERROR checking out $branch" -ForegroundColor Red
        return
    }

    # Attempt merge
    git merge main 2>&1 | Out-Null
    
    # Check if there are conflicts
    $conflicts = git diff --name-only --diff-filter=U 2>&1
    if (-not $conflicts) {
        Write-Host "No conflicts - already merged or clean" -ForegroundColor Green
        git commit --no-edit 2>&1 | Out-Null
        return
    }

    Write-Host "Resolving conflicts in: $($conflicts -join ', ')" -ForegroundColor Yellow

    # ── 1. RepositoryServiceProvider.php ─────────────────────────────────────
    if ($conflicts -contains "app/Providers/RepositoryServiceProvider.php") {
        $headContent = (git show HEAD:app/Providers/RepositoryServiceProvider.php 2>&1) -join "`n"
        # Add Documento binding if not already present
        if ($headContent -notmatch "DocumentoRepositoryInterface") {
            $headContent = $headContent -replace '(\s+\$this->app->bind\(\\App\\Repositories\\Contracts\\MovimentacaoRepositoryInterface::class.*?\);)', '$1' + "`n        `$this->app->bind(\App\Repositories\Contracts\DocumentoRepositoryInterface::class, \App\Repositories\DocumentoRepository::class);"
        }
        [System.IO.File]::WriteAllText("$PWD\app\Providers\RepositoryServiceProvider.php", $headContent, [System.Text.Encoding]::UTF8)
        git add "app/Providers/RepositoryServiceProvider.php" 2>&1 | Out-Null
    }

    # ── 2. composer.json ──────────────────────────────────────────────────────
    if ($conflicts -contains "composer.json") {
        $headContent = (git show HEAD:composer.json 2>&1) -join "`n"
        $headContent = $headContent -replace '"version": "[^"]*"', '"version": "1.3.0"'
        $headContent = $headContent -replace '"name": "laravel/laravel"', '"name": "synapses/erp-ged-conselhos"'
        [System.IO.File]::WriteAllText("$PWD\composer.json", $headContent, [System.Text.Encoding]::UTF8)
        git add "composer.json" 2>&1 | Out-Null
    }

    # ── 3. README.md ──────────────────────────────────────────────────────────
    if ($conflicts -contains "README.md") {
        $mainContent = (git show MERGE_HEAD:README.md 2>&1) -join "`n"
        $mainContent = $mainContent -replace 'version-v1\.[0-9\.]+-?[a-z]*-(?:blue|orange)', 'version-v1.3.0-blue'
        $mainContent = $mainContent -replace 'status-em%20desenvolvimento-yellow', 'status-active-brightgreen'
        $mainContent = $mainContent -replace 'Synapses GED v1\.[0-9\.]+', 'Synapses GED v1.3.0'
        $mainContent = $mainContent -replace 'img.shields.io/badge/version-v1\.[0-9\.]+-blue', 'img.shields.io/badge/version-v1.3.0-blue'
        [System.IO.File]::WriteAllText("$PWD\README.md", $mainContent, [System.Text.Encoding]::UTF8)
        git add "README.md" 2>&1 | Out-Null
    }

    # ── 4. relatorio_evolucao.md ──────────────────────────────────────────────
    if ($conflicts -contains ".agents/relatorio_evolucao.md") {
        $mainContent = (git show MERGE_HEAD:.agents/relatorio_evolucao.md 2>&1) -join "`n"
        $headContent = (git show HEAD:.agents/relatorio_evolucao.md 2>&1) -join "`n"
        
        # Extract v1.3.0 section from HEAD if present
        if ($headContent -match '(?s)(## \[v1\.3\.0\].*?)(\n---\n\*Assinado)') {
            $v130Block = $Matches[1].Trim()
            $finalContent = $mainContent -replace '(\n---\n\*Assinado por.*)', "`n`n---`n`n$v130Block`n`n---`n*Assinado por: Antigravity AI*"
        } else {
            $finalContent = $mainContent
        }
        [System.IO.File]::WriteAllText("$PWD\.agents\relatorio_evolucao.md", $finalContent, [System.Text.Encoding]::UTF8)
        git add ".agents/relatorio_evolucao.md" 2>&1 | Out-Null
    }

    # ── 5. resources/views/processos/show.blade.php ───────────────────────────
    if ($conflicts -contains "resources/views/processos/show.blade.php") {
        # Use HEAD version (has UI classes) and add document section from main
        # Since HEAD already had Documents in some branches, take main version
        git checkout --theirs -- resources/views/processos/show.blade.php 2>&1 | Out-Null
        git add "resources/views/processos/show.blade.php" 2>&1 | Out-Null
    }

    # Check if still any unresolved
    $remaining = git diff --name-only --diff-filter=U 2>&1
    if ($remaining) {
        Write-Host "Still unresolved: $remaining" -ForegroundColor Red
        return
    }

    # Commit
    git commit -m "chore: merge main and resolve conflicts in $branch" 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "SUCCESS: $branch merged and committed" -ForegroundColor Green
    } else {
        Write-Host "COMMIT ERROR for $branch" -ForegroundColor Red
    }
}

# All remaining branches
$branches = @(
    "code-health-refactoring-robustness-14558464457667003548",
    "code-health-standardization-9906402544894110602",
    "davinci-movimentacoes-processo-158777652681095457",
    "palette-ux-accessibility-improvements-8825228644303007839",
    "palette-ux-improvements-3040260369357449010",
    "palette-ux-improvements-v1.3.0-15603856584315116134",
    "palette/ux-accessibility-refactor-6086848420503778759",
    "performance-optimization-bolt-9777789427802752289",
    "security-admin-access-control-12972953059133422690"
)

foreach ($b in $branches) {
    Resolve-Branch -branch $b
}

Write-Host "`n=== All branches processed ===" -ForegroundColor Cyan
git checkout main
