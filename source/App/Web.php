<?php

namespace Source\App;

use Cassandra\Date;
use CoffeeCode\Router\Router;
use League\Plates\Engine;
use Source\Models\Contact;
use Source\Models\Email;
use Source\Models\Notification;
use Source\Models\Report;
use Source\Models\Organ;
use Source\Models\Upload;
use Source\Models\User;

/**
 * Class Web
 *
 * @package Source\App
 */
class Web
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Engine
     */
    private $view;

    /**
     * Web constructor.
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = Engine::create(THEMES, 'php');
        $this->view->addData([
            'router' => $router,
        ]);

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * @return void
     */
    public function home(): void
    {
        echo $this->view->render('home', [
            'title' => 'Inicio | ' . SITE
        ]);
    }

    /**
     * @return void
     */
    public function reports(): void
    {
        $reports = (new Report())->find()->fetch(true);

        echo $this->view->render('mapReports', [
            'title' => 'Denúncias | ' . SITE,
            'points' => $reports
        ]);
    }

    /**
    * @return void
     * Page error
    */
    public function error(array $data): void
    {
        echo $this->view->render('error', [
            'title' => "Erro {$data['errcode']}| " . SITE,
            'error' => $data['errcode'],
        ]);
    }

    /**
     * @return void
     * Login page
     */
    public function login(): void
    {
        $this->ifLogged();

        echo $this->view->render('login', [
           'title' => 'Login | ' . SITE
        ]);
    }

    /**
     * @return void
     * Validate Login
     */
    public function validateLogin($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $user = (new User())->find('registration = :registration AND password = :password', 'registration='. $data['registration'] . '&password='. md5($data['password']))->fetch();
        if($user){
            $_SESSION['user']['id'] = $user->id;
            $_SESSION['user']['registration'] = $user->registration;
            $_SESSION['user']['name'] = $user->name;
            $_SESSION['user']['organ'] = $user->organ;

            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * @return void
     * Profile page
     */
    public function profile(): void
    {
        $this->ifNotLogged();

        $user = (new User())->findById($_SESSION['user']['id']);
        $notifications = (new Notification())->find('user_registration = :registration', 'registration='. $user->registration, 'id')->fetch(true);
        $organ = (new Organ())->find('id = :id', 'id='. $user->organ, 'name')->fetch();
        $organGroup = (new User())->find('organ = :organ', 'organ='. $user->organ, 'id, name, image')->fetch(true);

        if($notifications){
            $aux = count($notifications);
        }else{
            $aux = 0;
        }

        echo $this->view->render('profile', [
            'title' => 'Perfil | ' . SITE,
            'user' => $user,
            'notificationCount' => $aux,
            'organ' => $organ->name,
            'organGroup' => $organGroup
        ]);
    }

    /**
     * @return void
     * Create report page
     */
    public function createReport(): void
    {
        $this->ifNotLogged();

        $reports = (new Report())->find('', '', 'id, type, title')->limit(2)->fetch(true);

        echo $this->view->render('createReport', [
            'title' => 'Cadastrar denúncia | ' . SITE,
            'reports' => $reports
        ]);
    }

    /**
     * @return void
     * Report info page
     */
    public function reportInfo(array $data): void
    {
        $this->ifNotLogged();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $report = (new Report())->findById($data['id']);
        if ($report) {
            $notifications = (new Notification())->find('report_id = :id', 'id='. $data['id'])->fetch(true);

            $uploads = array();
            $aux = 1;

            $attachments = (new Upload())->find('report_id = :id', 'id='. $report->id)->fetch(true);
            if($attachments) {
                foreach ($attachments as $attach) {
                    $ext = explode('.', $attach->file_name);
                    $uploads[] = ['fileName' => 'anexo-' . $aux . '.'. $ext[1], 'localName' => $attach->file_name];
                    $aux++;
                }
            }

            echo $this->view->render('reportInfo', [
                'title' => 'Informação da denúncia | ' . SITE,
                'report' => $report,
                'notifications' => $notifications,
                'uploads' => $uploads
            ]);
        } else {
            $this->router->redirect('listReports');
        }
    }

    /**
     * @param array $data
     * @return void
     * Open file get method
     */
    public function openFile(array $data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $this->ifNotLogged();

        $file = (new Upload())->find('file_name = :fileName', 'fileName='. $data['fileName'])
            ->fetch(false);

        if(!$file) {
            $file = (new Notification())->find('file_name = :fileName', 'fileName=' . $data['fileName'])
                ->fetch(false);
        }

        $fileName = $file->file_name;
        $filePath = $file->file_path;

        $ext = explode('.', $fileName);
        $fileToDownload = file_get_contents($filePath);

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=\"anexo.{$ext[1]}\"");
        header("Content-Description: PHP Generated Data");

        echo($fileToDownload);
    }

    /**
     * @return void
     * Export excel data method
     */
    public function exportData(): void
    {
        $this->ifNotLogged();

        function reportType($type) {
            switch ($type) {
                case 1:
                    return 'Estabelecimento Irregular';
                    break;
                case 2:
                    return 'Descarte Irregular de Lixo';
                    break;
                case 4:
                    return 'Buraco na via';
                    break;
                case 5:
                    return 'Lampada queimada';
                    break;
                case 6:
                    return 'Calçada irregular';
                    break;
                default:
                    return 'Tipo não identificado';
                    break;
            }
        }

        function checkStatus($status) {
            switch ($status) {
                case 0:
                    return 'Pendente';
                    break;
                case 1:
                    return 'Concluído';
                    break;
                default:
                    return 'Status não identificado';
                    break;
            }
        }

        function returnAgent($registration) {
            $user = (new User())
                ->find('registration = :registration', 'registration='. $registration, 'name')
                ->fetch(false);

            if ($user) {
                return $user->name;
            } else {
                return null;
            }
        }

        // File Name
        $file_name = 'denuncias.xls';

        // File Head
        $html = '';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td colspan="5"><b>Listagem de denúncias - Geo-Report</b></td>';
        $html .= '</tr>';

        // File Fields
        $html .= '<tr>';
        $html .= '<td><b>#</b></td>';
        $html .= '<td>Prazo</td>';
        $html .= '<td>Tipo</td>';
        $html .= '<td>Título</td>';
        $html .= '<td>Descrição</td>';
        $html .= '<td>Processo</td>';
        $html .= '<td>Inscrição</td>';
        $html .= '<td>Status</td>';
        $html .= '<td>Agente</td>';
        $html .= '</tr>';

        $reports = (new Report())->find()->fetch(true);
        if ($reports) {
            $aux = 1;
            foreach ($reports as $report) {
                $html .= '<tr>';
                $html .= '<td><b>'. $aux .'</b></td>';
                $html .= '<td>'. $report->date .'</td>';
                $html .= '<td>'. reportType($report->type) .'</td>';
                $html .= '<td>'. $report->title .'</td>';
                $html .= '<td>'. $report->description .'</td>';
                $html .= '<td>'. $report->process .'</td>';
                $html .= '<td>'. $report->property_registration .'</td>';
                $html .= '<td>'. checkStatus($report->status) .'</td>';
                $html .= '<td>'. returnAgent($report->user_registration) .'</td>';
                $html .= '</tr>';
                $aux++;
            }
        }

        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header ("Cache-Control: no-cache, must-revalidate");
        header ("Pragma: no-cache");
        header ("Content-type: application/x-msexcel");
        header ("Content-Disposition: attachment; filename=\"{$file_name}\"" );
        header ("Content-Description: PHP Generated Data" );
        // Envia o conteúdo do arquivo

        echo $html;
    }

    /**
     * @return void
     * Create report page
     */
    public function listReports(): void
    {
        $this->ifNotLogged();

        $reports = (new Report())->find()->fetch(true);

        echo $this->view->render('listReports', [
            'title' => 'Listar denúncias | ' . SITE,
            'reports' => $reports
        ]);
    }

    /**
     * @return void
     * Delete Report method
     */
    public function deleteReport($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $notifications = (new Notification())->find('report_id = :reportId', 'reportId='. $data['reportId'])
            ->fetch(true);

        if($notifications) {
            foreach ($notifications as $notification) {
                $notification->destroy();
                if($notification->fail()) {
                    var_dump($notification->fail->getMessage());
                }
            }
        }

        $report = (new Report())->findById($data['reportId']);
        if($report) {
            $report->destroy();
        }

        if($report->fail()) {
            var_dump($report->fail()->getMessage());
        } else {
            echo 1;
        }
    }

    /**
     * @return void
     * Delete Attachment method
     */
    public function deleteAttach($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        $attachment = (new Upload())->find('file_name = :fileName', 'fileName='. $data['fileName'])->fetch();

        if($attachment) {
            if(file_exists($attachment->file_path)) {
                unlink($attachment->file_path);
                $attachment->destroy();
                echo 1;
            } else {
                echo 1;
            }
        }
    }

    /**
     * @return void
     * Validate report page
     */
    public function validateReport($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if(is_uploaded_file($_FILES['fileToUpload']['tmp_name'])){
            $target_file = basename($_FILES['fileToUpload']['name']);

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $extensions_arr = array('jpg','jpeg','png');

            if(in_array($imageFileType,$extensions_arr) ){
                $image_base64 = base64_encode(file_get_contents($_FILES['fileToUpload']['tmp_name']));
                $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;

                $size = getImageSizeFromString(base64_decode($image_base64));

                $ext = substr($size['mime'], 6);

                if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                    die('Unsupported image type');
                }
            }

            if($data['process']){
                $process = $data['process'];
            } else {
                $process = null;
            }

            $report = new Report();

            $report->type = $data['type'];
            $report->subtype = 51;
            $report->user_registration = $_SESSION['user']['registration'];
            $report->organ = $data['organ'];
            $report->platform = 1;
            $report->process = $process;
            $report->image = $image;
            $report->title = $data['title'];
            $report->description = $data['description'];
            $report->property_registration = $data['propertyRegistration'];
            $report->date = $data['date'];
            $report->save();

            if($data['latitude'] && $data['longitude']) {
                $lat = $data['latitude'] + 0;
                $lng = $data['longitude'] + 0;
                $point = 'POINT('. $lat . ' ' . $lng .')';

                $report->latitude = $data['latitude'];
                $report->longitude = $data['longitude'];
                $report->accuracy = 1;
//                $report->point = $point;
//                $report->save(['polygon']);
                $report->save();

                // Tratamento do Json dos bairros
                $json_neighborhood = file_get_contents(url('themes/assets/json/Bairros.json'));
                $json_array = json_decode($json_neighborhood)->features;
                $neighborhood_count = count($json_array) - 1;

                // Declaração de variavéis
                $array = array();

                // Passa o Json com os bairros para um array que deixa ele em ordem crescente quanto aos seus id's
                for ($i = 0; $i <= $neighborhood_count; $i++) {
                    $keynumber = $json_array[$i]->properties->ID_BAIRROS;
                    $array[$keynumber] = $json_array[$i];
                }

                // Ordena o array em ordem crescente
                sort($array);

                $neighborhood_id = 51;

                // Repete a função para cada um dos bairros existentes no json
                for ($i = 0; $i <= $neighborhood_count; $i++) {
                    $array_point = array();
                    // Pega cada geopoint do poligono para adicionar no array de pontos
                    foreach ($array[$i]->geometry->coordinates[0] as $point) {
                        // Adiciona a latitude e longitude da região ao array
                        $array_point[] = $point[1] . " " . $point[0];
                    }

                    //  Adiciona uma virgula entre cada casa do array e transforma ele em uma string
                    $str = implode(',', $array_point);

                    // Utiliza a string para criar o nome da função do poligono
                    $polygon = 'POLYGON((' . $str . '))';

                    // Criação do poligono e consulta se há alguma denúncia dentro dele

                    $auxNeighborhood = (new Report())
                        ->find('ST_CONTAINS(ST_GEOMFROMTEXT("'. $polygon . '"), point) AND id = :reportId',
                            'reportId='. $report->id)
                        ->fetch(false);

                    if($auxNeighborhood) {
                        $report->neighborhood_id = $auxNeighborhood->id;
                    }

                    $report->save();

                    // Elimina o array de pontos
                    unset($array_point);
                }
            }

            $folder =  THEMES . '/assets/uploads';
            if(!file_exists($folder) || !is_dir($folder)){
                mkdir($folder, 0755);
            }
            $fileName = $_SESSION['user']['registration'] . '-' . $report->id . '-' . time() . '.' .$ext;
            $dir = $folder . '/' . $fileName;

            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dir);

            $upload = new Upload();
            $upload->user_registration = $_SESSION['user']['registration'];
            $upload->report_id = $report->id;
            $upload->file_name = $fileName;
            $upload->file_path = $dir;
            $upload->save();

            if($report->fail() || $upload->fail()){
                var_dump($report->fail()->getMessage(), $upload->fail()->getMessage());
            } else {
                echo 1;
            }
        } else {
            exit();
        }
    }

    public function validateNotification($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if(is_uploaded_file($_FILES['fileToUpload']['tmp_name'])){
            $notification = new Notification();
            $notification->report_id = $data['reportId'];
            $notification->status = $data['status'];
            $notification->user_registration = $_SESSION['user']['registration'];
            $notification->user_name = $_SESSION['user']['name'];
            $notification->title = $data['title'];
            $notification->description = $data['description'];
            $notification->save();

            $target_file = basename($_FILES['fileToUpload']['name']);

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $extensions_arr = array("jpg","jpeg","png");

            if(in_array($imageFileType,$extensions_arr) ){
                $image_base64 = base64_encode(file_get_contents($_FILES['fileToUpload']['tmp_name']));
                $size = getImageSizeFromString(base64_decode($image_base64));
                $ext = substr($size['mime'], 6);
            }

            $folder =  THEMES . '/assets/uploads';
            if(!file_exists($folder) || !is_dir($folder)){
                mkdir($folder, 0755);
            }
            $fileName = $_SESSION['user']['registration'] . '-' . $notification->id . '-' . $data['reportId'] . '-' . time() . '.' . $ext;
            $dir = $folder . '/' . $fileName;

            $notification->file_path = $dir;
            $notification->file_name = $fileName;
            $notification->save();

            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dir);

            if($data['status'] == 1) {
                $report = (new Report())->findById($data['reportId']);
                $report->status = 1;
                $report->save();

                if($report->fail()){
                    var_dump($report->fail()->getMessage());
                }
            }

            if($notification->fail()){
                var_dump($notification->fail()->getMessage());
            } else {
                $user = (new User())->findById($_SESSION['user']['id']);
                $user->called++;
                $user->save();
                if(!$user->fail()){
                    echo 1;
                }
            }
        } else {
            exit();
        }
    }

    /**
     * @return void
     * Validate upload file method
     */
    public function validateUpload($data): void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        if(is_uploaded_file($_FILES['fileToUpload']['tmp_name'])){
            $target_file = basename($_FILES['fileToUpload']['name']);

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $extensions_arr = array("jpg","jpeg","png");

            if(in_array($imageFileType,$extensions_arr) ){
                $image_base64 = base64_encode(file_get_contents($_FILES['fileToUpload']['tmp_name']));
                $size = getImageSizeFromString(base64_decode($image_base64));
                $ext = substr($size['mime'], 6);
            }

            $folder =  THEMES . '/assets/uploads';
            if(!file_exists($folder) || !is_dir($folder)){
                mkdir($folder, 0755);
            }
            $fileName = $_SESSION['user']['registration'] . '-' . $data['reportId'] . '-' . time() . '.' . $ext;
            $dir = $folder . '/' . $fileName;

            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $dir);

            $upload = new Upload();
            $upload->user_registration = $_SESSION['user']['registration'];
            $upload->report_id = $data['reportId'];
            $upload->file_name = $fileName;
            $upload->file_path = $dir;
            $upload->save();

            if($upload->fail()){
                var_dump($upload->fail()->getMessage());
            } else {
                echo 1;
            }
        }
    }

    /**
     * @return void
     * Sign Out
     */
    public function logout(): void
    {
        if(!empty($_SESSION['user']['id'])){
            unset($_SESSION['user']);
        }

        $this->router->redirect('web.home');
    }

    /**
     * @return void
     * Check if logged
     */
    public function ifNotLogged(): void
    {
        if(!isset($_SESSION['user']['id'])){
            $this->router->redirect('web.home');
        }
    }

    /**
     * @return void
     * Check if logged
     */
    public function ifLogged(): void
    {
        if(isset($_SESSION['user']['id'])){
            $this->router->redirect('web.profile');
        }
    }
}
