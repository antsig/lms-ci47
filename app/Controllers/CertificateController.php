<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CertificateModel;

class CertificateController extends BaseController
{
    protected $certificateModel;

    public function __construct()
    {
        $this->certificateModel = new CertificateModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'Certificate Templates',
            'certificates' => $this->certificateModel->getCertificates()
        ];
        return view('admin/certificates/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Certificate Template'
        ];
        return view('admin/certificates/builder', $data);
    }

    public function store()
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'template_data' => $this->request->getPost('template_data'),  // JSON from builder
        ];

        // Handle background image upload
        $bg = $this->request->getFile('background_image');
        if ($bg && $bg->isValid()) {
            $newName = $bg->getRandomName();
            $bg->move(WRITEPATH . '../public/uploads/certificates', $newName);
            $data['background_image'] = $newName;
        }

        $this->certificateModel->save($data);

        return redirect()->to('/admin/certificates')->with('success', 'Certificate template created.');
    }

    public function edit($id)
    {
        $cert = $this->certificateModel->find($id);
        if (!$cert) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Certificate Template',
            'certificate' => $cert
        ];
        return view('admin/certificates/builder', $data);
    }

    public function update($id)
    {
        $data = [
            'id' => $id,
            'title' => $this->request->getPost('title'),
            'template_data' => $this->request->getPost('template_data'),
        ];

        $bg = $this->request->getFile('background_image');
        if ($bg && $bg->isValid()) {
            $newName = $bg->getRandomName();
            $bg->move(WRITEPATH . '../public/uploads/certificates', $newName);
            $data['background_image'] = $newName;
        }

        $this->certificateModel->save($data);
        return redirect()->to('/admin/certificates')->with('success', 'Template updated.');
    }

    public function delete($id)
    {
        $this->certificateModel->delete($id);
        return redirect()->back()->with('success', 'Template deleted.');
    }
}
