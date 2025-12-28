document.addEventListener("DOMContentLoaded", function () {
  const cepInput = document.getElementById("cep");
  if (!cepInput) return;

  cepInput.addEventListener('input', function () {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 8) value = value.slice(0, 8);
    if (value.length > 5) {
      value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    }
    this.value = value;
  });

  cepInput.addEventListener("blur", function () {
    // Remove non-digits
    const cep = this.value.replace(/\D/g, "");

    if (cep !== "") {
      // Regex for CEP validation
      const validacep = /^[0-9]{8}$/;

      if (validacep.test(cep)) {
        // Determine fields based on ID existence
        const enderecoInput =
          document.getElementById("endereco") ||
          document.getElementById("logradouro");
        const bairroInput = document.getElementById("bairro");
        const cidadeInput = document.getElementById("cidade");
        const ufInput = document.getElementById("uf");

        // Fill with "..." while fetching
        if (enderecoInput) enderecoInput.value = "...";
        if (bairroInput) bairroInput.value = "...";
        if (cidadeInput) cidadeInput.value = "...";
        if (ufInput) ufInput.value = "...";

        // Use local proxy to avoid CORS issues
        const baseUrl = window.baseUrl || '/';
        const fetchUrl = `${baseUrl}/utils/consultaCep/${cep}`.replace(/([^:]\/)\/+/g, "$1"); // Normalize slashes
        fetch(fetchUrl)
          .then((response) => response.json())
          .then((data) => {
            if (!("erro" in data)) {
              if (enderecoInput) enderecoInput.value = data.logradouro;
              if (bairroInput) bairroInput.value = data.bairro;
              if (cidadeInput) cidadeInput.value = data.localidade;
              if (ufInput) ufInput.value = data.uf;
            } else {
              alert("CEP não encontrado.");
              cleanForm();
            }
          })
          .catch((error) => {
            console.error("Error fetching CEP:", error);
            alert("Erro ao buscar CEP.");
            cleanForm();
          });
      } else {
        alert("Formato de CEP inválido.");
        cleanForm();
      }
    } else {
      cleanForm();
    }

    function cleanForm() {
      const enderecoInput =
        document.getElementById("endereco") ||
        document.getElementById("logradouro");
      const bairroInput = document.getElementById("bairro");
      const cidadeInput = document.getElementById("cidade");
      const ufInput = document.getElementById("uf");

      if (enderecoInput) enderecoInput.value = "";
      if (bairroInput) bairroInput.value = "";
      if (cidadeInput) cidadeInput.value = "";
      if (ufInput) ufInput.value = "";
    }
  });
});
