<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Custodian;
use App\Models\Location;
use App\Models\Mda;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\GDLibRenderer;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    // create asset
    public function store(Request $request)
    {
        
        $fields = Validator::make($request->all(),[
            'tagnumber'=> 'required|string',
        ]);
        

        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }
        
        $user = auth()->user(); 

                
        $validateTag = Asset::where('tagnumber', $request->tagnumber)->get();

        if(count($validateTag) < 0) {
            $response = [
                'message'=> "Tag doesn't exists",
                'success' => false
            ];
            return response($response);
        }else {
            // Array to store file paths
            $filePaths = [];
            $baseUrl = url('/').'/storage';
    
            // Define the file inputs
            $fileInputs = ['pict1', 'pict2', 'pict3', 'pict4', 'video'];
    
            // Loop through each file input
            foreach ($fileInputs as $index => $input) {
                // Check if file exists for the input
                if ($request->hasFile($input)) {
                    // Get the file from the request
                    $file = $request->file($input);
                    // Store the file
                    $filePaths['path' . ($index + 1)] = $baseUrl . '/' . $file->store('uploads', 'public');
                }
            }
    
            // Create the asset with file paths and other data
            $assetData = array_merge(
                [
                    'tagnumber' => $request->tagnumber,
                    'assetcategory'=> $request->assetcategory,
                    'assettype'=> $request->assettype,
                    'assetname'=> $request->assetname,
                    'assetdescription'=> $request->assetdescription,
                    'landsize'=> $request->landsize,
                    'arealocated'=> $request->arealocated,
                    'landmark'=> $request->landmark,
                    'gpslocation'=> $request->gpslocation,
                    'make'=> $request->make,
                    'modelno'=> $request->modelno,
                    'serialno'=> $request->serialno,
                    'colour'=> $request->colour,
                    'year'=>$request->year,
                    'engineno'=> $request->engineno,
                    'chasisno'=> $request->chasisno,
                    'vehicleno'=> $request->vehicleno,
                    'datepurchase'=> $request->datepurchase,
                    'purchasedamount'=> $request->purchasedamount,
                    'status'=> $request->status,
                    'mdaid'=> $request->mdaid,
                    'assetlocation'=> $request->assetlocation,
                    'locationdescription'=> $request->locationdescription,
                    'custodian'=> $request->custodian,
                    'submittedby'=> $request->submittedby,
                    'user_id'=> $user->id,    
                    'pict1' => isset($filePaths['path1']) ? $filePaths['path1'] : null,
                    'pict2' => isset($filePaths['path2']) ? $filePaths['path2'] : null,
                    'pict3' => isset($filePaths['path3']) ? $filePaths['path3'] : null,
                    'pict4' => isset($filePaths['path4']) ? $filePaths['path4'] : null,
                    'video' => isset($filePaths['path5']) ? $filePaths['path5'] : null,
                ],
            );
    
    
            $asset = $validateTag->first();

            // Update the model attributes
            $asset->fill($assetData);

            // Save the changes to the database
            $asset->save();
            
            $response = [
                'asset'=> $asset,
                'message'=> "submit successful",
                'success' => true
            ];
            return response($response);
        
        }


    }


    // register tag and get qrcode
    public function registerTag(Request $request) {
        
        $fields = Validator::make($request->all(),[
            'tagnumber'=> 'required|string',
        ]);
        

        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }
        
        $user = auth()->user();

        $validateTag = Asset::where('tagnumber', $request->tagnumber)->get();

        if(count($validateTag)>0){
            $response = [
                'message'=> "Tag already exists",
                'success' => false
            ];
            return response($response);
        }else{
            $asset = Asset::create([
                'tagnumber' => $request->tagnumber,
            ]);
    
            $data = mb_convert_encoding($request->tagnumber, 'UTF-8', 'auto');
    
            // Create QR code renderer with GDLibRenderer
            $renderer = new GDLibRenderer(400);
            
            // Create QR code writer
            $writer = new Writer($renderer);
        
            // Generate QR code as a string
            $qrCodeString = $writer->writeString($data);
        
            // Generate and save the QR code as an image file
            $fileName = $writer->writeFile($data, 'qrcode.png');
        
            // Encode the QR code string using base64
            $qrCodeBase64 = base64_encode($qrCodeString);
    
    
    
        
            $response = [
                'qrcode' => $qrCodeBase64,
                'response' => true,
            ];
            return response($response);
        }

        


    

    }


    // create mda
    public function createMda(Request $request) {     
        $fields = Validator::make($request->all(),[
            'mda_name'=> 'required|string',
        ]);
        

        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }

        $mda = Mda::create([
            'mda_name'=> $request->mda_name,
        ]);

        
        $response = [
            'mda'=> $mda,
            'message'=> "MDA created successfully",
            'success' => true
        ];
        return response($response);



    }


    // list of mda with location and custodian
    public function mdaList(Request $request) {     
        $fields = Validator::make($request->all(),[
            'mdaid'=> 'required',
        ]);
        

        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }

        $mda = Mda::with(['locations', 'custodian'])->where('id', $request->mdaid)->get();

        
        $response = [
            'mdalist'=> $mda,
            'message'=> "MDA list retrieved successfully",
            'success' => true
        ];
        return response($response);



    }


    public function createLocation(Request $request) {
        $fields = Validator::make($request->all(),[
            'mdaid'=> 'required',
            'locationname' => 'required'
        ]);
        
        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }

        $location = Location::create([
            'locationname'=> $request->locationname,
            'mdaid'=> $request->mdaid,
        ]);

        $response = [
            'location'=> $location,
            'message'=> "location created successfully",
            'success' => true
        ];
        return response($response);

    }


    // create custodian
    public function createCustodian(Request $request) {
        $fields = Validator::make($request->all(),[
            'mdaid'=> 'required',
            'custodianname' => 'required'
        ]);
        
        if($fields->fails()) {
            $response = [
                'errors'=> $fields->errors(),
                'success' => false
            ];

            return response($response);
        }

        $Custodian = Custodian::create([
            'custodianname'=> $request->custodianname,
            'mdaid'=> $request->mdaid,
        ]);

        $response = [
            'Custodian'=> $Custodian,
            'message'=> "Custodian created successfully",
            'success' => true
        ];
        return response($response);

    }


    // get all assets created
    public function myList () {
        $user = auth()->user(); 
        $assets = Asset::where('user_id', $user->id)->get();

        $totalVerified = Asset::where('user_id', $user->id)->where('status', 'Approved')->get();

        
        $response = [
            'Assets'=> $assets,
            "totalsubmited"=> count($assets),
            "totalverified"=> count($totalVerified),
            'message'=> "mylist retrieved successfully",
            'success' => true
        ];
        return response($response);



    }
    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        //
    }
}
