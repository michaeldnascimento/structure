<?php

namespace App\Core;

use Sinergi\BrowserDetector as BD;
use \DateTimeZone as DateTimeZone;
use \DateTime as DateTime;
use \PDO as PDO;
use Verot\Upload\Upload;


class Tools {

    static function dd($data, $dump = false)
    {
        echo '<pre>';
        ($dump) ? var_dump($data) : print_r($data);
        echo '</pre>';
    }

    static function debug($data, $dump = false)
    {
        echo '<pre>';
        ($dump) ? var_dump($data) : print_r($data);
        echo '</pre>';
    }

    /**
     * Processo de tratamento para o mecanismo MVC
     *
     */
    static function filteredName(string $input): string
    {
        $input = explode('?', $input);
        $input = $input[0];

        $find = [
            '-',
            '_'
        ];
        $replace = [
            ' ',
            ' '
        ];
        return str_replace(' ', '', ucwords(str_replace($find, $replace, $input)));
    }

    static function filteredFileName($input): string
    {
        $input = trim($input);

        //Remove " caso exista
        $new = str_replace('&#34;', '', $input);

        $find = [
            '  ', '"', 'á', 'ã', 'à', 'â', 'ª', 'é', 'è', 'ê', 'ë',
            'í', 'ì', 'î', 'ï', 'ó', 'ò', 'õ', 'ô', '°', 'º', 'ö',
            'ú', 'ù', 'û', 'ü', 'ç', 'ñ', 'Á', 'Ã', 'À', 'Â', 'É',
            'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Õ', 'Ô',
            'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç', 'Ñ'
        ];

        $replace = [
            '', '', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i',
            'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u',
            'u', 'u', 'c', 'n', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E',
            'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U',
            'U', 'C', 'N'
        ];

        return strtolower(str_replace(' ', '_', str_replace($find, $replace, $new)));
    }

    static function decamelize($cameled, $sep = '-'): string
    {
        return implode(
            $sep, array_map(
                'strtolower', preg_split('/([A-Z]{1}[^A-Z]*)/', $cameled, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)
            )
        );
    }

    static function associateToObject($ass) {
        if (!is_array($ass))
            return $ass;

        $obj = new \stdClass();

        foreach ($ass as $k => $v) {
            $obj->$k = $v;
        }

        return $obj;
    }

    /**
     * Verified se o usuário esta logado.
     *
     * @return Boolean      Booleano informando se está logado.
     */
    static function estaLogado(): bool
    {
        return isset($_SESSION['usuario']);
    }

    /**
     * Obtem o login da sessão.
     *
     * */
    static function getLogin() {
        if (self::estaLogado()) {
            return $_SESSION['usuario']->email;
        }
        return NULL;
    }

    /**
     * Obtem os dados da sessao do usuário logado.
     * */
    static function getLogin2() {
        if (self::estaLogado()) {
            return $_SESSION['usuario']->login;
        }
        return NULL;
    }

    /**
     * Obtem os dados da sessao do usuário logado, buscando o nome.
     * */
    static function getNome() {
        if (self::estaLogado()) {
            return $_SESSION['usuario']->nome;
        }
        return NULL;
    }

    /**
     * Obtem os dados da sessao do nome login usuario logado.
     * */
    static function getDadosSessao() {
        if (self::estaLogado()) {
            $dados = self::associateToObject($_SESSION['info']);
            $dados->login = self::getLogin();
            return $dados;
        }
        return NULL;
    }

    /**
     * Obtem uma string com a numeração dos grupos do sistema.
     * @return String
     * */
    static function getGrupoSistema(): string
    {
        $temp = str_replace("[", "", Template::$idturma);
        $temp = str_replace("]", "", $temp);

        return $temp;
    }

    static function PadronizaNome($str): string
    {
        $search = array(
            "Á", "À", "Â", 'Ä', "Ã",
            "É", "È", "Ê", "Ë",
            "Í", "Ì", "Î", "Ï",
            "Ó", "Ò", "Ô", "Ö", "Õ",
            "Ú", "Ù", "Û", "Ü",
            "Ç", "\'"
        );

        $searchmin = array(
            "á", "à", "â", 'ä', "ã",
            "é", "è", "ê", "ë",
            "í", "ì", "î", "ï",
            "ó", "ò", "ô", "ö", "õ",
            "ú", "ù", "û", "ü",
            "ç", "\""
        );

        $replace = array(
            "A", "A", "A", "A", "A",
            "E", "E", "E", "E",
            "I", "I", "I", "I",
            "O", "O", "O", "O", "O",
            "U", "U", "U", "U",
            "C", " ");

        $replacemin = array(
            "a", "a", "a", "a", "a",
            "e", "e", "e", "e",
            "i", "i", "i", "i",
            "o", "o", "o", "o", "o",
            "u", "u", "u", "u",
            "c", " ");

        $nomeP = str_replace($search, $replace, $str);
        $nomeP = str_replace($searchmin, $replacemin, $nomeP);

        return(strtoupper($nomeP));
    }

    static function PadronizaNomeM($str): string
    {
        $search = array(
            "Á", "À", "Â", 'Ä', "Ã",
            "É", "È", "Ê", "Ë",
            "Í", "Ì", "Î", "Ï",
            "Ó", "Ò", "Ô", "Ö", "Õ",
            "Ú", "Ù", "Û", "Ü",
            "Ç", "\'", " "
        );

        $searchmin = array(
            "á", "à", "â", 'ä', "ã",
            "é", "è", "ê", "ë",
            "í", "ì", "î", "ï",
            "ó", "ò", "ô", "ö", "õ",
            "ú", "ù", "û", "ü",
            "ç", "\"", " "
        );

        $replace = array(
            "A", "A", "A", "A", "A",
            "E", "E", "E", "E",
            "I", "I", "I", "I",
            "O", "O", "O", "O", "O",
            "U", "U", "U", "U",
            "C", " ", "");

        $replacemin = array(
            "a", "a", "a", "a", "a",
            "e", "e", "e", "e",
            "i", "i", "i", "i",
            "o", "o", "o", "o", "o",
            "u", "u", "u", "u",
            "c", " ", "");

        $nomeP = str_replace($search, $replace, $str);
        $nomeP = str_replace($searchmin, $replacemin, $nomeP);

        return(strtolower($nomeP));
    }



    /**
     * Obtem informações do visitante como IP, Navegador, Versão do Navegador, Sistema Operacional, Dispositivo, Linguagem
     *
     */
    static function getDadosVisitante(): array
    {
        $browser = new BD\Browser();
        $os = new BD\Os();
        $device = new BD\Device();
        $language = new BD\Language();

        return [
            "navegador" => $browser->getName(),
            "versao" => $browser->getVersion(),
            "ip" => self::getIp(),
            "so" => $os->getName(),
            "linguagem" => $language->getLanguage(),
            "dispositivo" => $device->getName()
        ];
    }

    /**
     * Obtem uma string com o Navegador e a versao do usuario
     * @return String
     * */
    static function getNavegador(): string
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'IE';
        } elseif (preg_match('|Trident/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'IE';
        } elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Opera';
        } elseif (preg_match('|Opera Mini/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Opera Mini';
        } elseif (preg_match('|Firefox/([0-9.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Firefox';
        } elseif (preg_match('|Chrome/([0-9.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Chrome';
        } elseif (preg_match('|Safari/([0-9.]+)|', $useragent, $matched)) {
            $browser_version = $matched[1];
            $browser = 'Safari';
        } else {
            // browser not recognized!
            $browser_version = 0;
            $browser = 'other';
        }

        return "$browser $browser_version";
    }

    /**
     * Obtem o número ip do usuário.
     * */
    static function getIp(): string
    {
        return getenv("REMOTE_ADDR");
    }

    /**
     * Obtem a data atual no formato yyyy-mm-dd.
     * */
    static function getDate(): string
    {
        return date("Y-m-d");
    }

    /**
     * Obtem a data atual no formato dd/mm/yyyy.
     * */
    static function getDateBr(): string
    {
        return date("d/m/Y");
    }

    /**
     * Obtem a hora atual no formato hh:mm:ss.
     * */
    static function getTime(): string
    {
        return date("H:i:s");
    }

    /**
     * Obtem a data e hora atual no formato yyyy-mm-dd hh:mm:ss.
     * */
    public static function getDateTime(): string
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * Obtem a data e hora atual no formato dd-mm-yyyy hh:mm:ss.
     * */
    public static function getDateTimeBr(): string
    {
        return date("d/m/Y H:i:s");
    }

    /**
     * Tirando a mascara do cpf e completando com zero a esquerda
     * */
    static function cpfSemMascara($cpf): string
    {
        if (empty($cpf)) {
            return $cpf;
        }

        $novoCpf = str_replace(".", "", $cpf);
        $novoCpf = str_replace("-", "", $novoCpf);
        while (strlen($novoCpf) < 11) {
            $novoCpf = "0" . $novoCpf;
        }

        return $novoCpf;
    }

    /**
     * Colocando a mascara do cpf e completando com zero a esquerda
     * */

    static function cpfComMascara($cpf): string
    {
        if (empty($cpf)) {
            return $cpf;
        }

        while (strlen($cpf) < 11) {
            $cpf = "0" . $cpf;
        }
        $cpf = substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9);

        return $cpf;
    }

    /**
     * Colocando a mascara (00) 0000-0000 no telefone ou (00) 0000-00000 para celular
     *
     * */
    static function telefoneComMascara($telefone): string
    {
        switch (strlen($telefone)) {
            case 8:
                $a = substr($telefone, 0, 4);
                $b = substr($telefone, 4);

                $telefone = '(11) ' . $a . '-' . $b;

                break;
            case 9:
                $a = substr($telefone, 0, 4);
                $b = substr($telefone, 4);

                $telefone = '(11) ' . $a . '-' . $b;

                break;
            case 0:
                $telefone = '(00) 0000-0000';

                break;
        }

        return $telefone;
    }

    /**
     * Colocando a data no FORMATA YYYY-MM-DD
     * */
    static function dataBanco($data): string
    {
        if (empty($data)) {
            return $data;
        }

        $dia = substr($data, 0, 2);
        $mes = substr($data, 3, 2);
        $ano = substr($data, 6);
        $datafinal = $ano . "-" . $mes . "-" . $dia;
        return $datafinal;
    }

    /**
     * Colocando a data no FORMATA YYYY-MM-DD hh:mm:ss.
     * */
    public static function dataHoraBanco($data): string
    {
        if (empty($data)) {
            return $data;
        }

        $dia = substr($data, 0, 2);
        $mes = substr($data, 3, 2);
        $ano = substr($data, 6, 4);
        $hora = substr($data, 10);
        $datafinal = $ano . "-" . $mes . "-" . $dia . " " . $hora;
        return $datafinal;
    }

    /**
     * Colocando a data no FORMATO DD/MM/YYYY
     * */
    static function dataBr($data): string
    {
        if (empty($data)) {
            return $data;
        }
        $dia = substr($data, 8, 2);
        $mes = substr($data, 5, 2);
        $ano = substr($data, 0, 4);
        $datafinal = $dia . "/" . $mes . "/" . $ano;
        return $datafinal;
    }

    /**
     * perga a data mm/dd/aa e transforma dd/dd/aa
     * Colocando a data no FORMATO DD/MM/YYYY
     * */
    static function covertDataBr($data): string
    {
        if (empty($data)) {
            return $data;
        }
        $mes = substr($data, 0, 2);
        $dia = substr($data, 3, 2);
        $ano = substr($data, 6);
        $datafinal = $dia . "/" . $mes . "/" . $ano;
        return $datafinal;
    }

    /**
     * DateTime para Date
     * */
    static function dateTimeToDate($data): string
    {
        if (empty($data)) {
            return $data;
        }

        if (strlen($data) == 10) {
            return $data;
        }

        $data = explode(" ", $data);

        return $data[0];
    }

    /**
     * DateTime para Time
     * */
    static function dateTimeToTime($data): string
    {
        if (empty($data)) {
            return $data;
        }

        $data = explode(" ", $data);

        return $data[1];
    }

    /**
     * Colocando a data no FORMATO DD/MM/YYYY hh:mm:ss.
     * */
    static function dataHoraBr($data)
    {
        if (empty($data)) {
            return $data;
        }
        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, 2);
        $dia = substr($data, 8, 2);
        $hora = substr($data, 10);

        $datafinal = $dia . "/" . $mes . "/" . $ano . " " . $hora;
        return $datafinal;
    }

    /**
     * Comparando as datas Assumido que $dataEntrada e $dataSaida estao em formato DD/MM/YYYY.
     *
     * @param String $dataEntrada Espeara a string no formato DD/MM/YYYY.
     * @param String $dataSaida Espeara a string no formato DD/MM/YYYY.
     *
     * */
    static function comparaDatas(string $dataEntrada, string $dataSaida): string
    {

        if (is_null($dataEntrada) AND is_null($dataSaida)) {
            return 'igual';
        }

        $timeZone = new DateTimeZone('UTC');

        /** Assumido que $dataEntrada e $dataSaida estao em formato dia/mes/ano */
        $data1 = DateTime::createFromFormat('d/m/Y', $dataEntrada, $timeZone);
        $data2 = DateTime::createFromFormat('d/m/Y', $dataSaida, $timeZone);

        /** Testa se sao validas */
        if (!($data1 instanceof DateTime)) {
            return -1;
        }

        if (!($data2 instanceof DateTime)) {
            return -1;
        }

        if ($data1 > $data2) {
            return 'maior';
        }

        if ($data1 == $data2) {
            return 'igual';
        }

        if ($data1 < $data2) {
            return 'menor';
        }
        return '';
    }

    /**
     * Converte uma string em Data assumido que a string $stringData esteja no formato DD/MM/YYYY.
     *
     * @param String $stringData Espera a string no formato DD/MM/YYYY.
     *
     * @return DateTime
     * */
    static function stringToDate(String $stringData): DateTime
    {
        $timeZone = new DateTimeZone('UTC');

        $data = DateTime::createFromFormat('d/m/Y', $stringData, $timeZone);

        return $data;
    }

    /**
     * Converte uma string em DataTime assumido que a string $stringDataTime esteja no formato DD/MM/YYYY 00:00:00.
     *
     * @param String $stringDataTime Espera a string no formato DD/MM/YYYY 00:00:00.
     *
     * @return DateTime
     * */
    static function stringToDateTime($stringDataTime) {
        $timeZone = new DateTimeZone('UTC');

        $data = DateTime::createFromFormat('d/m/Y H:i:s', $stringDataTime, $timeZone);

        return $data;
    }

    /**
     * Get script url
     *
     * @return array Retorno o array com a url e get
     */
    static function getUrlAtual(): array
    {
        $url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' .
            // $url = 'http://' .
            $_SERVER['SERVER_NAME'] .
            // ($_SERVER['SERVER_PORT'] == 80 ? '' : ':' .
            // $_SERVER['SERVER_PORT']) .
            $_SERVER['REQUEST_URI'];

        $get = '';
        if (!strpos($url, '?') === false) {
            list($url, $get) = explode('?', $url);
        }

        return ['url' => $url, 'get' => $get];
    }

    /**
     *
     * @param String $string_cifrada
     * @param String $data_hora_string no formato 'YYYY-MM-DD HH:MM'
     * @return string
     */
    static function decifra(String $string_cifrada, string $data_hora_string): string
    {

        $datetime_expression_array = explode(' ', $data_hora_string);
        $data_string = $datetime_expression_array[0];    // no formato 'YYYY-MM-DD'
        $hora_string = $datetime_expression_array[1];    // no formato 'HH:MM:SS'


        $data_string_array = explode('-', $data_string);
        $mes = $data_string_array[1];    // formato 'MM'
        $dia = $data_string_array[2];    // formato 'DD'

        $hora_string_array = explode(':', $hora_string);
        $hora = $hora_string_array[0];    //formato 'HH'
        $minuto = $hora_string_array[1];   //formato 'MM'
        // Elimina os zeros iniciais (Ex: '02' vai ser alterado para '2')
        $mes = (int) $mes;
        $mes = (string) $mes;
        $dia = (int) $dia;
        $dia = (string) $dia;
        $hora = (int) $hora;
        $hora = (string) $hora;
        $minuto = (int) $minuto;
        $minuto = (string) $minuto;

        //
        //  chaverLI
        //
        //  chaverLI é uma chave gerada em função da data de criação da conta
        //  armazenada na coluna dtcria da tabela CONTA_C.
        //
        //  É constituída por dia, mês, hora e minuto da data de criação, sendo
        //  valores numéricos no formato de um ou dois dígitos (ex: 10, 6, 0, etc).
        //  Sendo assim, não contem valores como "01", "00", "06", etc, para dia,
        //  mês, hora e minuto.
        //
        $chave_rLI = $dia . $mes . $hora . $minuto;

        $pos_LI = $chave_rLI[1];
        $pos_LI = (int) $pos_LI;

        $giro_LI = $string_cifrada[$pos_LI];
        $giro_LI = (int) $giro_LI;

        $decifrando = mb_substr($string_cifrada, 0, $pos_LI) . // ler pos_LI caracteres a partir do início
            mb_substr($string_cifrada, ($pos_LI + 1), 30);

        //echo '<br> decriptando etapa 1: '. $decifrando . '<br>';

        $decifrando = mb_substr($decifrando, (mb_strlen($decifrando) - $giro_LI), 30) .
            mb_substr($decifrando, 0, (mb_strlen($decifrando) - $giro_LI));

        // echo '<br> decriptando etapa 2: '. $decifrando . '<br>';

        $chave_LA = mb_substr($decifrando, 16, 2) .
            $decifrando[19];


        $chave_dorgLI = ((int) $chave_LA) - 99;


        $tam_La = $decifrando[22] .
            $decifrando[24];

        $tam_LI = ((int) $tam_La) - 57;

        $decifrando = mb_substr($decifrando, 0, 16) .
            $decifrando[18] .
            mb_substr($decifrando, 20, 2) .
            $decifrando[23];


        $chave_dLI = $chave_dorgLI; // int
        $string_decifrada = '';


        //$cLI = 0;
        for ($i = 0; $i < $tam_LI; $i++) {
            $caractere_atual = $decifrando[$i];
            //echo '<br> caracter atual: '.$caractere_atual. '<br>';
            $cLI = ord($caractere_atual);
            $cLI = $cLI - $chave_dLI;
            while ($cLI < 32) {
                $cLI = $cLI + 95;
            }
            $string_decifrada = $string_decifrada . chr($cLI);
            $chave_dLI = $chave_dLI + $cLI;
            while ($chave_dLI > 900) {
                $chave_dLI = $chave_dLI - 900;
            }
        }
        return $string_decifrada;
    }

    static function getDriversPDO(): array
    {
        return PDO::getAvailableDrivers();
    }

    function fotoUpload($nome, $pasta): array
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$pasta = ESTRUTURA_PATH . '..' . DS . 'docs' . DS . 'informes' . DS;

            $handle = new upload($_FILES['foto'], 'pt_BR');
            if ($handle->uploaded) {
                $handle->file_new_name_body = $nome;
                $handle->file_overwrite = true;
                $handle->file_max_size = '5M';
                $handle->allowed = array('image/*');
                $handle->file_new_name_ext = 'jpg';

                //$handle->image_resize = true;
                //$handle->image_x = 500;
                //$handle->image_ratio_y = true;

                $handle->process($pasta);
                if ($handle->processed) {
                    $handle->clean();

                    return [
                        "sucesso" => TRUE,
                        "msg" => '<li>Upload realizado com sucesso.</li>'
                    ];
                } else {
                    return [
                        "sucesso" => FALSE,
                        "msg" => "<li>$handle->error</li>"
                    ];
                }
            }
        }

        return [];
    }

    /**
     * crypt AES 256
     *
     */
    function encrypt(string $data, string $password): string
    {
        // Definir um salt aleatório
        $salt = openssl_random_pseudo_bytes(16);

        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
            $dx = hash('sha256', $dx . $password . $salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, true, $iv);
        return base64_encode($salt . $encrypted_data);
    }

    /**
     * decrypt AES 256
     *
     */
    function decrypt(string $edata, string $password) {
        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $rounds = 3; // depende do tamanho da chave
        $data00 = $password . $salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1] . $data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv = substr($result, 32, 16);

        return openssl_decrypt($ct, 'AES-256-CBC', $key, true, $iv);
    }

    /**
     *  Converte a string para letras minusculas
     *
     * @param string $string
     * @return string
     */
    function minuscula(string $string): string
    {
        return strtolower($string);
    }

    /**
     * Converte a string para letras maiusculas
     *
     * @param string $string
     * @return string
     */
    function maiuscula(string $string): string
    {
        return strtoupper($string);
    }

    /**
     * Retorno o $_POST do campo informado
     *
     * @param string $campo
     * @return string
     */
    function getPost(string $campo) {
        return (isset($_POST[$campo])) ? $_POST[$campo] : NULL;
    }

    /**
     * Retorna o $_GET do compo informado
     *
     * @param string $campo
     * @return string
     */
    function getGet(string $campo) {
        return (isset($_GET[$campo])) ? $_GET[$campo] : NULL;
    }

}
