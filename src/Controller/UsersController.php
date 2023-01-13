<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\View\View;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null && $session->read('role') == 0) {
        } else {
            $this->redirect(['action' => 'login']);
        }

        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    public function userindex()
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null && $session->read('role') == 1) {
            $id = $session->read('id');
            $user = $this->Users->get($id, [
                'contain' => [],
            ]);

            $this->set(compact('user'));
        } else {
            $this->redirect(['action' => 'login']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null && $session->read('role') == 0) {
            $user = $this->Users->get($id, [
                'contain' => [],
            ]);

            $this->set(compact('user'));
        } else {
            $this->redirect(['action' => 'login']);
        }
    }
    public function userview()
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null && $session->read('role') == 1) {
            $uid = $session->read('id');
            $user = $this->Users->get($uid, [
                'contain' => [],
            ]);

            $this->set(compact('user'));
        } else {
            $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $productImage = $this->request->getData("image");
            $fileName = $productImage->getClientFilename();
            $fileSize = $productImage->getSize();
            $data["image"] = $fileName;
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $hasFileError = $productImage->getError();

                if ($hasFileError > 0) {
                    $data["image"] = "";
                } else {
                    $fileType = $productImage->getClientMediaType();

                    if ($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg") {
                        $imagePath = WWW_ROOT . "img/" . $fileName;
                        $productImage->moveTo($imagePath);
                        $data["image"] = $fileName;
                    }
                }
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null && $session->read('role') == 0) {

            $user = $this->Users->get($id, [
                'contain' => [],
            ]);
            $fileName2 = $user['image'];

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $productImage = $this->request->getData("image");
                $fileName = $productImage->getClientFilename();
                if ($fileName == '') {
                    $fileName = $fileName2;
                }

                $data["image"] = $fileName;
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $hasFileError = $productImage->getError();
                    if ($hasFileError > 0) {
                        $data["image"] = "";
                    } else {
                        $fileType = $productImage->getClientMediaType();

                        if ($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg") {
                            $imagePath = WWW_ROOT . "img/" . $fileName;
                            $productImage->moveTo($imagePath);
                            $data["image"] = $fileName;
                        }
                    }
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
            $this->set(compact('user'));
        } else {
            $this->redirect(['action' => 'login']);
        }
    }

    public function useredit()
    {
        $session = $this->request->getSession();
        if ($session->read('email') != null && $session->read('role') == 1) {

            $uid = $session->read('id');
            $user = $this->Users->get($uid, [
                'contain' => [],
            ]);
            $fileName2 = $user['image'];

            if ($this->request->is(['patch', 'post', 'put'])) {
                $data = $this->request->getData();
                $productImage = $this->request->getData("image");
                $fileName = $productImage->getClientFilename();
                if ($fileName == '') {
                    $fileName = $fileName2;
                }

                $data["image"] = $fileName;
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $hasFileError = $productImage->getError();
                    if ($hasFileError > 0) {
                        $data["image"] = "";
                    } else {
                        $fileType = $productImage->getClientMediaType();

                        if ($fileType == "image/png" || $fileType == "image/jpeg" || $fileType == "image/jpg") {
                            $imagePath = WWW_ROOT . "img/" . $fileName;
                            $productImage->moveTo($imagePath);
                            $data["image"] = $fileName;
                        }
                    }
                    $this->Flash->success(__('The user has been saved.'));

                    return $this->redirect(['action' => 'userindex']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
            $this->set(compact('user'));
        } else {
            $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {


        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        $session = $this->request->getSession();
        if ($session->read('role') == 0) {
            return $this->redirect(['action' => 'index']);
        } elseif ($session->read('role') == 1) {
            $session->destroy();
            return $this->redirect(['action' => 'login']);
        }
    }

    public function login()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $password =  $this->request->getData('password');

            $users = TableRegistry::get("Users");
            $result = $users->find('all')->where(['email' => $email, 'password' => $password])->first();

            if ($result) {

                $role = $result['role'];
                $id = $result['id'];

                $session = $this->getRequest()->getSession();
                $session->write('email', $email);
                $session->write('role', $role);
                $session->write('id', $id);

                if ($role == 0) {
                    $this->Flash->success(__('The admin has been logged in successfully.'));
                    return $this->redirect(['action' => 'index']);
                } elseif ($role == 1) {
                    $this->Flash->success(__('The user has been logged in successfully.'));
                    return $this->redirect(['action' => 'userindex']);
                }
            }
            $this->Flash->error(__('Please enter valid credential..'));
        }
        $this->set(compact('user'));
    }

    public function logout()
    {
        $session = $this->request->getSession();
        $session->destroy();
        return $this->redirect(['action' => 'login']);
    }

    public function forgot()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $users = TableRegistry::get("Users");
            $user = $users->find('all')->where(['email' => $email])->first();
            if ($user) {
                $token = rand(10000, 100000);
                $user->token = $token;
                if ($users->save($user)) {
                    $mailer = new Mailer('default');
                    $mailer->setTransport('gmail');
                    $mailer->setFrom(['sachinsingh10101997@gmail.com' => 'Rahul']);
                    $mailer->setTo($email);
                    $mailer->setEmailFormat('html');
                    $mailer->setSubject('Reset password link');
                    $mailer->deliver('<a href="http://localhost:8765/users/reset?token=' . $token . '">Click here</a> for reset your password');

                    $this->Flash->success(__('Reset email send successfully.'));
                }
            } else {
                $this->Flash->error(__('Please enter valid credential..'));
            }
        }
        $this->set(compact('user'));
    }

    public function reset()
    {
        $user = $this->Users->newEmptyEntity();
        $token = $_REQUEST['token'];
        $users = TableRegistry::get("Users");
        $result = $users->find('all')->where(['token' => $token])->first();
        if ($result) {
            if ($this->request->is('post')) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                $password = $this->request->getData('password');
                $res1 = preg_match('(^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]*).{8,}$)', $password);
                $confirm_password = $this->request->getData('confirm_password');
                if ($res1 == 1 && $confirm_password == $password) {
                    $result->password = $password;
                    $result->token = NULL;
                    if ($users->save($result)) {
                        $this->Flash->success(__('Password updated successfully.'));
                        return $this->redirect(['action' => 'login']);
                    }
                }
                $this->Flash->error(__('Please enter valid password'));
            }
        } else {
            return $this->redirect(['action' => 'login']);
        }

        $this->set(compact('user'));
    }
}
