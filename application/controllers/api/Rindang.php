<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Rindang extends REST_Controller {

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
        //header("Access-Control-Allow-Origin: https://data.rdgjt.com");
        //header("Access-Control-Allow-Origin: http://192.168.10.106:8080/");
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        $id = $this->get('id');
        if( $id === NULL ){
            $data = $this->rindang_model->getAllPotongan();
        } else {
            $data = $this->rindang_model->getAllPotongan($id);
        }
        $dataPotongan = $data->result_array();
        $jumlahData = $data->num_rows();
        if($dataPotongan){
            $this->response([
                'status' => true,
                'jumlahData' => $jumlahData,
                'data' => $dataPotongan
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'jumlahData' => $jumlahData,
                'message' => 'Erorr'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        
        //var_dump($dataPotongan);
    }

    public function index_delete(){
        $id = $this->delete('id');

        if($id === null){
            $this->response([
                'status' => false,
                'message' => 'NO TOKEN'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if($this->rindang_model->deleteInspect($id) > 0){
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'Success deleted'
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Erorr token not detected'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    } //END
    // public function index_delete(){
    //     $id = $this->delete('id');

    //     if($id === null){
    //         $this->response([
    //             'status' => false,
    //             'message' => 'NO TOKEN'
    //         ], REST_Controller::HTTP_BAD_REQUEST);
    //     } else {
    //         if($this->rindang_model->deletePotongan($id) > 0){
    //             $this->response([
    //                 'status' => true,
    //                 'id' => $id,
    //                 'message' => 'Success deleted'
    //             ], REST_Controller::HTTP_OK);
    //         } else {
    //             $this->response([
    //                 'status' => false,
    //                 'message' => 'Erorr token not detected'
    //             ], REST_Controller::HTTP_BAD_REQUEST);
    //         }
    //     }
    // } //END
    public function index_post(){
        $data = [
            'kode_potongan' =>  $this->post('kode'),
            'ukuran_ori' =>  $this->post('ori'),
            'ukuran_bs' =>  $this->post('bs'),
            'ukuran_bp' =>  $this->post('bp'),
            'operator' =>  $this->post('operator'),
            'kode_roll' =>  $this->post('koderoll'),
            'posisi' =>  $this->post('posisi'),
            'tgl_inspect_aja'  =>  $this->post('tgl'),
        ];
        if($this->rindang_model->createInspect($data) > 0){
            $this->response([
                'status' => true,
                'message' => 'Success Added Data',
                'kodeakses' => 200
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed To Save',
                'kodeakses' => 404
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

    } //END

    // public function index_post(){
    //     $data = [
    //         'id_produksi_mesin' => $this->post('idProduksiMesin'),
    //         'id_beam_sizing' => $this->post('idBeamSizing'),
    //         'tgl_potong' => $this->post('idTglPotong'),
    //         'waktu_potong' => $this->post('waktuPotong'),
    //         'ukuran_meter' => $this->post('ukuranMeter'),
    //         'shift' => $this->post('shift'),
    //         'operator' => $this->post('operator'),
    //         'konstruksi' => $this->post('konstruksi'),
    //         'no_beam' => $this->post('noBeam'),
    //         'keter' => $this->post('keter')
    //     ];
    //     if($this->rindang_model->createPotongan($data) > 0){
    //         $this->response([
    //             'status' => true,
    //             'message' => 'Success Added Data'
    //         ], REST_Controller::HTTP_CREATED);
    //     } else {
    //         $this->response([
    //             'status' => false,
    //             'message' => 'Failed To Save'
    //         ], REST_Controller::HTTP_BAD_REQUEST);
    //     }

    // } //END

    public function index_put(){
        $id = $this->put('id');
        $data = [
            'id_produksi_mesin' => $this->put('idProduksiMesin'),
            'id_beam_sizing' => $this->put('idBeamSizing'),
            'tgl_potong' => $this->put('idTglPotong'),
            'waktu_potong' => $this->put('waktuPotong'),
            'ukuran_meter' => $this->put('ukuranMeter'),
            'shift' => $this->put('shift'),
            'operator' => $this->put('operator'),
            'konstruksi' => $this->put('konstruksi'),
            'no_beam' => $this->put('noBeam'),
            'keter' => $this->put('keter')
        ];
        if($this->rindang_model->updatePotongan($data, $id) > 0){
            $this->response([
                'status' => true,
                'message' => 'Success Update Data'
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed To update'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    

}
