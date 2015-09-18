<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Controller\Plugin\Cache;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        /**
         * função anônima para var_dump estilizado
         */
//        $myVarDump = function($nome_linha = "Nome da Linha", $data = null, $caracter = ' - ') {
//                    echo str_repeat($caracter, 100) . '<br/>' . ucwords($nome_linha) . '<pre><br/>';
//                    var_dump($data);
//                    echo '</pre>' . str_repeat($caracter, 100) . '<br/><br/>';
//                };
// 
//        /**
//         * conexão com banco
//         */
//        $adapter = $this->getServiceLocator()->get('AdapterDb');
// 
//        /**
//         * obter nome do sehema do nosso banco
//         */
//        $myVarDump(
//                "Nome Schema", 
//                $adapter->getCurrentSchema()
//        );
// 
//        /**
//         * contar quantidade de elementos da nossa tabela
//         */
//        $myVarDump(
//                "Quantidade elementos tabela contatos", 
//                $adapter->query("SELECT * FROM `contatos`")->execute()->count()
//        );
// 
//        /**
//         * montar objeto sql e executar
//         */
//        $sql        = new \Zend\Db\Sql\Sql($adapter); // use Zend\Db\Sql\Sql
//        $select     = $sql->select()->from('contatos');
//        $statement  = $sql->prepareStatementForSqlObject($select);
//        $resultsSql = $statement->execute();
//        $myVarDump(
//                "Objet Sql com Select executado",
//                $resultsSql
//        );
// 
//        /**
//         * motar objeto resultset com objeto sql e mostrar resultado em array
//         */
//        $resultSet = new \Zend\Db\ResultSet\ResultSet; // nao necessita do use devido a sintaxe iniciando em \
//        $resultSet->initialize($resultsSql);
//        $myVarDump(
//                "Resultado do Objeto Sql para Array ",
//                $resultSet->toArray()
//        );
//        die();
        /**
        * Uso de cache
        */
//       if (!$this->cache()->hasItem('nome')) {
//           $myVarDump(
//               "Registro de Cache Agora", 
//               $this->cache()->setItem('nome', 'igor')
//           );
//       } else {
//           $myVarDump(
//               "Cache Existente", 
//               $this->cache()->getItem('nome')
//           );   
//       }
//        die();
        return new ViewModel();
    }
    
    public function sobreAction()
    {
        return new ViewModel();
    }
}
