<?php

namespace App\Managers\Template;


class Template
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($data)
    {
        $response = [];
        try {
            if($data->isEmpty()){
                $response["message"] = "No data yet!";
                $response["error"] = false;
            }else{
                $response["message"] = "Success";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        $response = [];
        try {
            if(!$data){
                $response["message"] = "No data found!";
            }else{
                $response["message"] = "Success";
                $response["data"] = $data;
                $response["error"] = false;
            }
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($data)
    {
        $response = [];
        try {
            $data->delete();
            $response["message"] = "Successfully Deleted";
            $response["error"] = false;
        } catch (\Exception $error) {
            $response["message"] = "Error ".$error->getMessage();
            $response["error"] = true;
        }
        return $response;
    }

    public function NoData()
    {
        $response = [];
        $response["message"] = "No data found!";
        $response["error"] = false;

        return $response;
    }


}


?>
