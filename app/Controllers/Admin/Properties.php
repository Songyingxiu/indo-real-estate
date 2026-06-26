<?php namespace App\Controllers\Admin;
/**
 * @author
 */
use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyTypeModel; 
use App\Models\StateModel;
use App\Models\CityModel; // ADDED: City Model for AJAX

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
        
        // Fetch Master Data for the dropdown menus
        $data['propertyTypes'] = $propertyTypeModel->findAll();
        $data['states'] = $stateModel->where('status', 'Active')->findAll();
        
        return view('admin/properties/create', $data);
    }

    public function store()
    {
        $propertyModel = new PropertyModel();
        $imageModel = new PropertyImageModel();
        
        $data = [
            'title'            => $this->request->getPost('title'),
            'description'      => $this->request->getPost('description'),
            'listing_type'     => $this->request->getPost('listing_type'),
            'property_type_id' => $this->request->getPost('property_type_id'),
            'state_id'         => $this->request->getPost('state_id'), // Ensures region is saved
            'city_id'          => $this->request->getPost('city_id'),  // Ensures city is saved
            'tax_price'        => $this->request->getPost('tax_price'),
            'bed'              => $this->request->getPost('bed'),
            'bath'             => $this->request->getPost('bath'),
            'total_land_area'  => $this->request->getPost('total_land_area'),
            'usable_area'      => $this->request->getPost('usable_area'),
            'parking'          => $this->request->getPost('parking'),
            'total_parking'    => $this->request->getPost('total_parking'),
            'total_floors'     => $this->request->getPost('total_floors'),
            'year_built'       => $this->request->getPost('year_built'),
            'address_line_1'   => $this->request->getPost('address_line_1'),
            'owner_id'         => session()->get('user_id'),
            'approval_status'  => 'Pending Review',
            'status'           => 'Active',
        ];

        $propertyModel->insert($data);
        $propertyId = $propertyModel->getInsertID();

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

        $shmFile = $this->request->getFile('shm_document');
        if ($shmFile && $shmFile->isValid() && ! $shmFile->hasMoved()) {
            $shmName = $shmFile->getRandomName();
            $shmFile->move(FCPATH . 'uploads/documents', $shmName);
        }

        return redirect()->to(base_url('admin/properties'))->with('success', 'Property successfully submitted for moderation!');
    }
    
    public function getCities($stateId)
    {
        $cityModel = new CityModel();
        $cities = $cityModel->where('state_id', $stateId)->findAll();
        
        return $this->response->setJSON($cities);
    }
}