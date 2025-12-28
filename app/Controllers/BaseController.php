<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * 
     */
    protected $dadosTemplate = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
        $this->dadosTemplate = $this->dadosTemplate();
    }

    private function dadosTemplate()
    {
        return [
            'titulo' => 'Synapses',
            'logoEmpresa' => 'public/dist/assets/img/synapses.png',
            'nomeEmpresa' => 'Synapses',
        ];
    }

        /**
     * Carrega uma view mesclando os dados fornecidos com os dados de navegação.
     *
     * @param string $view Nome da view a ser carregada.
     * @param array $data Dados específicos para a view.
     * @return string HTML da view renderizada.
     */
    public function loadView($view, $data = [])
    {
        $data = array_merge($data, $this->dadosTemplate); // Mescla os dados de nav() com os dados da view
        return view($view, $data);
    } 
}
