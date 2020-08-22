<?php


namespace Controller;


use Core\Config;
use Model\UserModel;
use mysqli;
use View\View;

class UsersController extends AbstractTableController
{
    protected $tableName = "users";
    protected $templateFolder = "users";

    public function __construct(View $view, mysqli $link)
    {
        parent::__construct($view, $link);
        $this->table = new UserModel(
            $this->tableName,
            $link
        );
    }

    public function actionShow(array $data)
    {
        parent::actionShow($data);
        $this->view->addData([
            'groupsList' => $this->table->getGroups(),
            'table' => $this
                ->table
                ->getUsers(
                    Config::PAGE_SIZE,
                    $data['get']['page'] ?? 1
                )
        ]);
    }

    public function actionShowEdit(array $data)
    {
        $_SESSION['crc'] = $this->table->getCrc($data['get']['id']);
        parent::actionShowEdit($data);
        $this->view->addData([
            'groupsList' => $this->table->getGroups()
        ]);
    }

    public function actionAdd(array $data)
    {
        $data['post']['password'] = md5(md5($data['post']['password']) . Config::SALT);
        parent::actionAdd($data);
    }

    public function actionEdit(array $data)
    {
        $data['post']['password'] = (
            !empty($data['post']['password']) and
            ($_SESSION['crc'] != md5(md5($data['post']['password']) . Config::SALT))
        ) ? md5(md5($data['post']['password']) . Config::SALT) : $_SESSION['crc'];
        parent::actionEdit($data);
    }

}