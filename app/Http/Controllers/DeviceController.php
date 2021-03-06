<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Traits\Json;
use DB;
use App\Device;
use App\Record;
use JavaScript;
use Auth;

class DeviceController extends Controller
{
    use Json;

    public function update(Request $req){
        $data = $req->get('data');
        $data = collect(json_decode($data, true));

        $data->each(function($val){
            $device = Device::where('mac', $val['mac'])->first();
            if(empty($device))
                $device = new Device($val);

            //new record, attach record with device
            $device->fill($val);
            $device->save();

            $record = new Record(['device_id' => $device->id]);
            $record->save();
        });

        //after update, save record @@

        return response(['msg'=>'success'], 200, $this->jsonHeader());
    }
    
    public function add(Request $req){
        if($req->method() == 'GET'){
            //load all device on network
            $devices = Device::all();

            JavaScript::put([
                'devices' => $devices
            ]);

            $userDevices = $devices->filter(function($device){
                return $device->user_id == Auth::id();
            });
            //load all device from user
//            $devices = Device::where('user_id', Auth::id())->get();
//
            JavaScript::put([
                'userDevices' => $userDevices
            ]);

            return view('device.add');
        }
        
        if($req->method() == 'POST'){
            //need auth to attach user-device
            if(!Auth::check())
                return redirect('login');

            $deviceInfo = $req->get('device');
            $device = Device::where('mac', $deviceInfo['mac'])->first();

            if(empty($device))
                $device = new Device();

            $device->fill($deviceInfo);
            $device->user_id = Auth::id();
            $device->save();

//            return response(['msg'=>'success'], 200, $this->jsonHeader());
            return redirect()->back();
        }
    }

    public function allByUser(){
        $devices = Device::where('user_id', Auth::id())->get();

        JavaScript::put([
            'userDevices' => $devices
        ]);

        return $devices;
    }

}
