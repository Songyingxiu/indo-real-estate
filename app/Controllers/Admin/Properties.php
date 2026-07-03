<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyTypeModel; 
use App\Models\StateModel;
use App\Models\CityModel; 
use App\Models\FeatureModel;
use App\Models\PropertyFeatureModel;

class Properties extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $roleId = session()->get('role_id');
        $userId = session()->get('user_id');

        if ($roleId == 4) {
            $data['properties'] = $propertyModel->findAll();
        } else {
            $data['properties'] = $propertyModel->where('owner_id', $userId)->findAll();
        }

        return view('admin/properties/properties', $data); 
    }

    public function create()
    {
        $propertyTypeModel = new PropertyTypeModel();
        $stateModel = new StateModel();
        $featureModel = new FeatureModel(); 
        
        $data['propertyTypes'] = $propertyTypeModel->findAll();
        $data['states'] = $stateModel->where('status', 'Active')->findAll();
        
        // Fetch active features from Master Data for the checkboxes
        $data['features'] = $featureModel->where('status', 'Active')->findAll();
        
        return view('admin/properties/create', $data);
    }

    public function store()
    {
        // --- 1. Form Validation ---
        $rules = [
            'title'            => 'required|min_length[5]|max_length[255]',
            'property_type_id' => 'required|numeric',
            'state_id'         => 'required|numeric',
            'city_id'          => 'required|numeric',
            'tax_price'        => 'required|numeric',
            'address_line_1'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $propertyModel = new PropertyModel();
        $imageModel = new PropertyImageModel();
        $propertyFeatureModel = new PropertyFeatureModel(); 
        
        // --- 2. Save Core Property Data ---
        $data = [
            'title'            => $this->request->getPost('title'),
            'description'      => $this->request->getPost('description'),
            'listing_type'     => $this->request->getPost('listing_type'),
            'property_type_id' => $this->request->getPost('property_type_id'),
            'state_id'         => $this->request->getPost('state_id'), 
            'city_id'          => $this->request->getPost('city_id'),  
            'tax_price'        => $this->request->getPost('tax_price'),
            'bed'              => $this->request->getPost('bed'),
            'bath'             => $this->request->getPost('bath'),
            'total_land_area'  => $this->request->getPost('total_land_area'),
            'usable_area'      => $this->request->getPost('usable_area'),
            'address_line_1'   => $this->request->getPost('address_line_1'),
            'owner_id'         => session()->get('user_id'),
            'approval_status'  => 'Draft', // Updated per July 7 requirements
            'status'           => 'Active',
        ];

        $propertyModel->insert($data);
        $propertyId = $propertyModel->getInsertID();

        // --- 3. Save Dynamic Features (Checkboxes) ---
        $selectedFeatures = $this->request->getPost('features');
        
        // Safety Check: Only save if the user actually checked some boxes
        if (!empty($selectedFeatures) && is_array($selectedFeatures)) {
            foreach ($selectedFeatures as $featureId) {
                $propertyFeatureModel->insert([
                    'property_id' => $propertyId,
                    'feature_id'  => $featureId,
                    'status'      => 'Active'
                ]);
            }
        }

        // --- 4. Save Images ---
        if ($imagefile = $this->request->getFiles()) {
            if (array_key_exists('property_images', $imagefile)) {
                $isFirst = true;
                foreach ($imagefile['property_images'] as $img) {
                    if ($img->isValid() && ! $img->hasMoved()) {
                        $newName = $img->getRandomName();
                        $img->move(FCPATH . 'uploads/properties', $newName);
                        
                        $imageModel->insert([
                            'property_id' => $propertyId,
                            'image_path'  => 'uploads/properties/' . $newName,
                            'is_primary'  => $isFirst ? 1 : 0 
                        ]);
                        $isFirst = false;
                    }
                }
            }
        }

        // --- 5. Save Documents ---
        $shmFile = $this->request->getFile('shm_document');
        if ($shmFile && $shmFile->isValid() && ! $shmFile->hasMoved()) {
            $shmName = $shmFile->getRandomName();
            $shmFile->move(FCPATH . 'uploads/documents', $shmName);
        }

        return redirect()->to(base_url('admin/properties'))->with('success', 'Property successfully saved as a Draft!');
    }
    
    public function getCities($stateId)
    {
        $cityModel = new CityModel();
        $cities = $cityModel->where('state_id', $stateId)->findAll();
        
        return $this->response->setJSON($cities);
    }
}