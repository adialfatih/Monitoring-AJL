<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Datamesin extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('rindang_model');
        $allowed_origins = [
            "https://data.rdgjt.com",
            "https://weaving.rdgjt.com",
            "https://inspect.rdgjt.com",
            "http://localhost:8080/"
        ];
        
        if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        }
        
    }

    
    public function index_get()
    {
        $id = $this->get('id');

        if ($id === NULL) {
            $dataMesin = $this->rindang_model->getAllMesin();
        } else {
            $dataMesin = $this->rindang_model->getAllMesin($id);
        }

        $jumlahMesin = count($dataMesin);

        // Inisialisasi rekap
        $mesinJalan = 0;
        $mesinStop = 0;
        $mesinSelesai = 0;
        $konstruksiJalan = [];

        foreach ($dataMesin as $mesin) {
            $status = strtolower($mesin['status_proses']);
            $konstruksi = $mesin['konstruksi'];

            if ($status === 'onproses') {
                $mesinJalan++;

                // Hitung jumlah mesin per konstruksi
                if (!isset($konstruksiJalan[$konstruksi])) {
                    $konstruksiJalan[$konstruksi] = 1;
                } else {
                    $konstruksiJalan[$konstruksi]++;
                }
            } elseif ($status === 'stop') {
                $mesinStop++;
            } elseif ($status === 'finish') {
                $mesinSelesai++;
            }
        }

        // Konversi jadi array key-value ke array objek
        $konstruksiJalanFormatted = [];
        foreach ($konstruksiJalan as $k => $v) {
            $konstruksiJalanFormatted[] = [
                'konstruksi' => $k,
                'jumlah_mesin' => $v
            ];
        }

        if ($dataMesin) {
            $this->response([
                'status' => true,
                'jumlahData' => $jumlahMesin,
                'konstruksiJalan' => $konstruksiJalanFormatted,
                'mesinJalan' => $mesinJalan,
                'mesinStop' => $mesinStop,
                'mesinSelesai' => $mesinSelesai,
                'data' => $dataMesin
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'jumlahData' => 0,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    

}
