<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// imort ModelContatoTable com alias
use Application\Model\ContatoTable as ModelContato;
use Zend\View\Model\ViewModel;

// import ContatoForm
use Application\Form\ContatoForm;

// import ModelContato
use Application\Model\Contato;

class ContatosController extends AbstractActionController
{
    
    protected $contatoTable;
        
    // GET /contatos
    public function indexAction()
    {

        // colocar parametros da url em um array
        $paramsUrl = [
            'pagina_atual'  => $this->params()->fromQuery('pagina', 1),
            'itens_pagina'  => $this->params()->fromQuery('itens_pagina', 10),
            'coluna_nome'   => $this->params()->fromQuery('coluna_nome', 'nome'),
            'coluna_sort'   => $this->params()->fromQuery('coluna_sort', 'ASC'),
            'search'        => $this->params()->fromQuery('search', null),
        ];

        // configuar método de paginação
        $paginacao = $this->getContatoTable()->fetchPaginator(
                /* $pagina */           $paramsUrl['pagina_atual'],
                /* $itensPagina */      $paramsUrl['itens_pagina'],
                /* $ordem */            "{$paramsUrl['coluna_nome']} {$paramsUrl['coluna_sort']}",
                /* $search */           $paramsUrl['search'],
                /* $itensPaginacao */   5
        );

        // retonar paginação mais os params de url para view
        return new ViewModel(['contatos' => $paginacao] + $paramsUrl);

    }
 
    // GET /contatos/novo
    public function novoAction()
    {
        return ['formContato' => new ContatoForm()];
    }
 
    // POST /contatos/adicionar
    public function adicionarAction()
    {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // instancia formulário
            $form = new ContatoForm();
            // instancia model contato com regras de filtros e validações
            $modelContato = new Contato();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
//            $form->setInputFilter($modelContato->getInputFilter());
            $modelContato->getInputFilter();
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para adicionar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelContato->exchangeArray($form->getData());
                // 2 - persistir dados do model para banco de dados
                $this->getContatoTable()->save($modelContato);

                // adicionar mensagem de sucesso
                $this->flashMessenger()
                        ->addSuccessMessage("Contato criado com sucesso!");

                // redirecionar para action index no controller contatos
                return $this->redirect()->toRoute('contatos');
            } else { // em caso da validação não seguir o que foi definido

                // renderiza para action novo com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formContato', $form)
                                ->setTemplate('application/contatos/novo');
            }
        }
    }
 
    // GET /contatos/detalhes/id
    public function detalhesAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem
            $this->flashMessenger()->addMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }

        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        // 2 - solicitar form com dados desse contato encontrado
        // formulário com dados preenchidos
//        $form = array(
//            'nome' => 'Igor Rocha',
//            "telefone_principal" => "(085) 8585-8585",
//            "telefone_secundario" => "(085) 8585-8585",
//            "data_criacao" => "02/03/2013",
//            "data_atualizacao" => "02/03/2013",
//        );
            // localizar adapter do banco
            
            try {
//                $form = (array) $this->getContatoTable()->find($id);
                $nome_cache_contato_id = "nome_cache_contato_{$id}";
                if (!$this->cache()->hasItem($nome_cache_contato_id)) {
                    $form = (array) $this->getContatoTable()->find($id);

                    $this->cache()->setItem($nome_cache_contato_id, $form);
                } else {
                    $form = (array) $this->cache()->getItem($nome_cache_contato_id);
                }
            } catch (Exception $exc) {
                // adicionar mensagem
                $this->flashMessenger()->addErrorMessage($exc->getMessage());

                // redirecionar para action index
                return $this->redirect()->toRoute('contatos');
            }

        // dados eviados para detalhes.phtml
//        return array('id' => $id, 'form' => $form);
        return (new ViewModel())
            ->setTerminal($this->getRequest()->isXmlHttpRequest())
            ->setVariable('id', $id)
            ->setVariable('form', $form);        
    }
 
    // GET /contatos/editar/id
    public function editarAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }

        // aqui vai a lógica para pegar os dados referente ao contato
        // 1 - solicitar serviço para pegar o model responsável pelo find
        // 2 - solicitar form com dados desse contato encontrado

        // formulário com dados preenchidos
//        $form = array(
//            'nome'                  => 'Igor Rocha',
//            "telefone_principal"    => "(085) 8585-8585",
//            "telefone_secundario"   => "(085) 8585-8585",
//        );

        try {
            $form = (array) $this->getContatoTable()->find($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            $this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('contatos');
        }        
        
        // dados eviados para editar.phtml
        return array('id' => $id, 'form' => $form);
    }
 
    // PUT /contatos/editar/id
    public function atualizarAction()
    {
        // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // obter e armazenar valores do post
            $postData = $request->getPost()->toArray();
            $formularioValido = true;

            // verifica se o formulário segue a validação proposta
            if ($formularioValido) {
                // aqui vai a lógica para editar os dados à tabela no banco
                // 1 - solicitar serviço para pegar o model responsável pela atualização
                // 2 - editar dados no banco pelo model

                // adicionar mensagem de sucesso
                $this->flashMessenger()->addSuccessMessage("Contato editado com sucesso");

                $nome_cache_contato_id = "nome_cache_contato_{$modelContato->id}";
                if ($this->cache()->hasItem($nome_cache_contato_id)) {
                    $this->cache()->removeItem($nome_cache_contato_id);
                }
                
                // redirecionar para action detalhes
                return $this->redirect()->toRoute('contatos', array("action" => "detalhes", "id" => $postData['id'],));
            } else {
                // adicionar mensagem de erro
                $this->flashMessenger()->addErrorMessage("Erro ao editar contato");

                // redirecionar para action editar
                return $this->redirect()->toRoute('contatos', array('action' => 'editar', "id" => $postData['id'],));
            }
        }
    }
 
    // DELETE /contatos/deletar/id
    public function deletarAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            $this->flashMessenger()->addMessage("Contato não encotrado");

        } else {
            // aqui vai a lógica para deletar o contato no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            // 2 - deleta contato

            // adicionar mensagem de sucesso
            $this->flashMessenger()->addSuccessMessage("Contato de ID $id deletado com sucesso");

        }

        // redirecionar para action index
        return $this->redirect()->toRoute('contatos');
    }
    
    private function getContatoTable() {
        // adicionar service ModelContato a variavel de classe
        if (!$this->contatoTable) {
            $this->contatoTable = $this->getServiceLocator()->get('ModelContato');
        }
 
        // return vairavel de classe com service ModelContato
        return $this->contatoTable;
    }
    
    // GET /contatos/search?query=[nome]
    public function searchAction()
    {
        $nome = $this->params()->fromQuery('query', null);
        if (isset($nome)) {
            $result = $this->getContatoTable()->search($nome);   
        } else  {
            $result = [];  
        }

        return new \Zend\View\Model\JsonModel($result);
    }    
}
