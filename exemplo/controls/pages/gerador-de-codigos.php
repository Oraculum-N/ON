<?php
    use ON\Alias;
use ON\App;
use ON\DBO;
use ON\Models;
use ON\Register;
use ON\Request;

$bancos = PDO::getAvailableDrivers();
    Register::set('bancos', $bancos);
    define('NL', "\n\r\t");
    $firstkey = null;

    if (post('send')):
        $banco = post('banco');
        $servidor = post('servidor');
        $usuario = post('usuario');
        $senha = post('senha');
        $base = post('base');
        $tabela = post('tabela');
        $arquivo = strtolower($tabela);
        $classe = ucwords($tabela);
        if ($base != ''):
            if ($banco == 'sqlite'):
                $model = '<'.'?'.'php'.NL;
                $model .= "\t".'$this->_dsntype=1;'.NL;
                $model .= "\t".'$this->_dsn=\''.$banco.':'.$base.'\';';
            else:
                $model = '<'.'?'.'php'.NL;
                $model .= "\t".'$this->_dsntype=1;'.NL;
                $model .= "\t".'$this->_dsn=\''.$banco.'://'.$usuario;
                $model .= ':'.$senha.'@'.$servidor.'/'.$base.'\';';
            endif;

            try {
                $db = new Models();

                if ($banco == 'sqlite'):
                    $db->setDsn($banco.':'.$base); else:
                    $db->setDsn($banco.'://'.$usuario.':'.$senha.
                                '@'.$servidor.'/'.$base);
                endif;
                $db->PDO();
                $dbo = new DBO(null);
                if ($banco != 'sqlite'):
                    DBO::execSQL('USE '.$base.';');
                $query = DBO::execSQL('SHOW KEYS FROM '.$tabela.
                                        ' WHERE Key_name = \'PRIMARY\';')
                        ->fetchAll();
                $keys = [];
                foreach ($query as $r):
                        if (is_null($firstkey)) {
                            $firstkey = $r['Column_name'];
                        }
                $keys[] = $r['Column_name'];
                endforeach;
                $keys = 'array(\''.implode('\',\'', $keys).'\')'; else:
                    $query = DBO::execSQL('PRAGMA table_info(\''.$tabela.'\');')
                        ->fetchAll();
                $keys = [$query[0]['name']];
                $keys = 'array(\''.implode('\',\'', $keys).'\')';

                endif;

                $control = '<'.'?'.'php'.NL.
                            'use ON\Models;'.NL.
                            'use ON\Request;'.NL.
                            'use ON\HTTP;'.NL.
                            'use ON\Register;'.NL.
                            'use ON\Adds;'.NL.
                            'use ON\\'.ucwords($tabela).' as '.ucwords($tabela).';'.NL.NL.

                            '// Carrega o arquivo de conexao com o banco'.NL.
                            '$db=new Models(\''.$banco.'\');'.NL.NL.
                            '// Carrega dinamicamente uma classe para a tabela '.$tabela.NL.
                            '$db->loadModelClass(\''.$tabela.'\');'.NL.
                            '$action=Request::getvar(\''.$arquivo.'\');'.NL.NL.
                            '// Verifica se a acao e\' permitida no sistema'.NL.
                            'if (!(in_array($action, array(\'cadastrar\', \'listar\', \'alterar\', \'excluir\'))))'.NL.
                            "\t".'$action=\'listar\';'.NL.NL.
                            '// Verifica se existe uma funcao com o mesmo nome da acao'.NL.
                            'if (is_callable($action))'.NL.
                            "\t".'call_user_func($action); // Chama a funcao'.NL.
                            'else'.NL.
                            '// Senao chama a funcao listar (esse e\' um tratamento de seguranca)'.NL.
                            'if (is_callable(\'listar\'))'.NL.
                            "\t".'call_user_func(\'listar\');'.NL.NL.
                            'function listar()'.NL.'{'.NL.
                            "\t".'// Carrega o plugin Datagrid'.NL.
                            "\t".'Adds::load(\'datagrid\');'.NL.
                            "\t".'// Cria uma instancia da classe'.NL.
                            "\t".'$tb=new '.$classe.'();'.NL.
                            "\t".'$tb->setKey('.$keys.');'.NL.
                            "\t".'// Carrega todos os registros da tabela/entidade'.NL.
                            "\t".'$regs=$tb->getAll();'.NL.
                            "\t".'// Cria uma instancia de do plugin Datagrid'.NL.
                            "\t".'$grid=new Datagrid($regs);'.NL.NL.
                            "\t".'// Define a classe CSS da tabela'.NL.
                            "\t".'$grid->setTableClass(\'table table-bordered table-striped\');'.NL.
                            "\t".'// Define a classe CSS do botao de atualizacao'.NL.
                            "\t".'$grid->setUpdateClass(\'btn btn-primary\');'.NL.
                            "\t".'// Define a classe CSS do botao de exclusao'.NL.
                            "\t".'$grid->setDeleteClass(\'btn btn-danger\');'.NL.
                            "\t".'// Determina o padrao de URL para o link de atualizar'.NL.
                            "\t".'$grid->setUpdateURL(URL.\''.$arquivo.'/alterar/%id%\');'.NL.
                            "\t".'// Determina o padrao de URL para o link de excluir'.NL.
                            "\t".'$grid->setDeleteURL(URL.\''.$arquivo.'/excluir/%id%#confirm%id%" onclick="if(confirm(\\\'Tem certeza que deseja excluir?\\\')){return true;}else{return false;}\');'.NL.
                            "\t".'// Define o label do botao de atualizacao'.NL.
                            "\t".'$grid->setUpdateLabel(\'<i class="icon-pencil icon-white"></i> Alterar\');'.NL.
                            "\t".'// Define o label do botao de exclusao'.NL.
                            "\t".'$grid->setDeleteLabel(\'<i class="icon-remove icon-white"></i> Excluir\');'.NL.
                            /*'	// Adiciona codigo HTML na celula de acoes (onde ficam os botoes)'.NL.
                            '	$grid->setAdictionalActionHTML(\'<div id="confirm%id%" class="modal hide fade">'.NL.
                            '		<div class="modal-header">'.NL.
                            '		  <button class="close" data-dismiss="modal">&times;</button>'.NL.
                            '		  <h3>Confirma&ccedil;&atilde;o</h3>'.NL.
                            '		</div>'.NL.
                            '		<div class="modal-body">'.NL.
                            '		  <p>Voc&ecirc; tem certeza que quer remover este registro?</p>'.NL.
                            '		</div>'.NL.
                            '		<div class="modal-footer">'.NL.
                            '		  <a href="'.URL.''.$arquivo.'/excluir/%id%" class="btn btn-primary">OK</a>'.NL.
                            '		  <a href="#" class="btn" data-dismiss="modal" >Cancelar</a>'.NL.
                            '		</div></div>\');'.NL.*/
                            "\t".'// Define o texto que deve ser exibido caso nao existem registros'.NL.
                            "\t".'$grid->setNoRecordsFound(\'Nenhum registro encontrado!\');'.NL.
                            "\t".'// Gera o HTML do grid'.NL.
                            "\t".'$grid=$grid->generate();'.NL.
                            "\t".'// Armazena o grid num registrador chamado grid que sera\' lido na view'.NL.
                            "\t".'Register::set(\'grid\', $grid);'.NL.
                            "\t".'// Armazena os registros num registrador chamado regs que sera\' lido na view'.NL.
                            "\t".'Register::set(\'regs\', $regs);'.NL.
                            '}'.NL.NL.

                            'function excluir()'.NL.'{'.NL.
                            "\t".'// Cria uma instancia da classe'.NL.
                            "\t".'$tb=new '.$classe.'();'.NL.
                            "\t".'$tb->setKey('.$keys.');'.NL.
                            "\t".'// Captura o que estiver apos /excluir/ na URL'.NL.
                            "\t".'$id=(int)Oraculum_Request::getvar(\'excluir\');'.NL.
                            "\t".'// Carrega todos os registros que tiverem o ID relacionado'.NL.
                            "\t".'$reg=$tb->getBy'.ucwords($firstkey).'($id);'.NL.
                            "\t".'if (sizeof($reg)>0):'.NL.
                            "\t\t".'// Se encontrar algum registro, o mesmo e\' apagado'.NL.
                            "\t\t".'$reg->delete();'.NL.
                            "\t".'endif;'.NL.
                            "\t".'// Redireciona para a pagina de listagem'.NL.
                            "\t".'HTTP::redirect(URL.\''.$arquivo.'\');'.NL.
                            '}'.NL.NL.

                            'function cadastrar()'.NL.'{'.NL.
                            "\t".'// No momento ainda e\' necessario criar manualmente o cadastro'.NL.
                            '}'.NL.NL.

                            'function alterar()'.NL.'{'.NL.
                            "\t".'// No momento ainda e\' necessario criar manualmente a alteracao'.NL.
                            '}'.NL.NL.

                            'if ($action==\'listar\'):'.NL.
                            "\t".'ON\App::loadView()'.NL.
                            "\t\t".'->addTemplate(\'geral\')'.NL.
                            "\t\t".'->loadPage(\''.$arquivo.'-listar\'); // Carrega a view de listagem'.NL.
                            'endif;';

                $viewlistar = '<'.'?'.'php $grid=Register::get(\'grid\'); ?'.'>'."\n\r".
                            '<'.'?'.'php echo $grid; ?'.'>';

                Register::set('model', $model);
                Register::set('control', $control);
                Register::set('viewlistar', $viewlistar);
                Register::set('nomebanco', $banco);
                Register::set('arquivo', $arquivo);
            } catch (Exception $e) {
                //Register::set('msg', 'Ocorreu algum problema ao capturar as informa&ccedil;&otilde;es do banco');
                echo $e->getMessage();
                exit;
                Alias::LoadAlias('Logs');
            }

        endif;
    endif;
    App::LoadView()
        ->AddTemplate('geral')
        ->LoadPage('gerador-de-codigos');
