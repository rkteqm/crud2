<?php

declare(strict_types=1);

namespace Cake\Controller\Admin;
use Cake\Controller\Controller;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminController extends Controller
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function adminPortal()
    {
        die('This is admin portal');
    }

    public function add()
    {
        die('This is admin signup');
    }
}
