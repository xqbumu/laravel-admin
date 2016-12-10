<?php

namespace Intendant\{$stub_intendant_zone_upper}\Controllers\Service;

use App\Models\Others\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderController extends Controller
{
    const ACTION_KEEP = 0;
    const ACTION_REMOVE = 1;

    /**
     * Field original value.
     *
     * @var mixed
     */
    protected $original;

    /**
     * Upload directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * File name.
     *
     * @var null
     */
    protected $filename = null;

    /**
     * Options for file-upload plugin.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Storage instance.
     *
     * @var string
     */
    protected $storage = '';

    /**
     * file_objs origin input file objects.
     *
     * @var object
     */
    protected $file_objs = null;

    /**
     * files result array.
     *
     * @var array
     */
    protected $files = array();

    /**
     * initial Preview result array.
     *
     * @var array
     */
    protected $initialPreview = array();

    /**
     * initial Preview Config result array.
     *
     * @var array
     */
    protected $initialPreviewConfig = array();

    /**
     * success process ok files number
     *
     * @var array
     */
    protected $success = 0;


    public function __construct()
    {
        $this->model = new Upload();
        $this->_initStorage();
    }

    public function store()
    {
        $this->file_objs = Input::file();

        foreach ($this->file_objs as $file) {
            if (is_array($file)) {
                foreach ($file as $index => $item) {
                    array_push($this->files, $this->_store($item));
                }
            } else {
                array_push($this->files, $this->_store($file));
            }
        }

        return array(
            'initialPreview' => $this->initialPreview,
            'initialPreviewConfig' => $this->initialPreviewConfig,
            'initialPreviewThumbTags' => array(),
            'error' => '',
            'total' => count($this->files),
            'success' => $this->success,
        );
    }

    public function destroy($id)
    {
        if ($this->_destroy($id)) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin::lang.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin::lang.delete_failed'),
            ]);
        }
    }

    /**
     * Destroy data entity and remove files.
     *
     * @param $id
     *
     * @return mixed
     */
    private function _destroy($id)
    {
        $ids = array_filter(explode(',', $id));

        foreach ($ids as $id) {
            if (empty($id) && $id !== 0) {
                continue;
            }

            $obj = $this->model->find($id);
            if($obj) {
                if($obj->url) $this->_deleteFilesAndImages($obj->url);
                $obj->delete();
            }
        }

        // if ($id==0 && Input::get('key')) {
        //     $this->_deleteFilesAndImages(Input::get('key'));
        // }

        return true;
    }

    /**
     * Remove files or images in record.
     *
     * @param $id
     */
    private function _deleteFilesAndImages($url)
    {
        if ($url && $this->storage->exists($url)) {
            $this->storage->delete($url);
        }
    }

    /**
     * Specify the directory and name for uplaod file.
     *
     * @param string      $directory
     * @param null|string $name
     *
     * @return $this
     */
    public function move($directory, $name = null)
    {
        $this->directory = $directory;

        $this->filename = $name;

        return $this;
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile $file
     *
     * @return mixed|string
     */
    private function _store(UploadedFile $file = null)
    {
        if (is_null($file)) {
            if ($this->_isDeleteRequest()) {
                return '';
            }
            return $this->original;
        }

        $this->directory = $this->directory ?: $this->_getStorePath($file);

        $this->filename = $this->filename ?: $file->getClientOriginalName();

        $save_res = $this->_uploadAndDeleteOriginal($file);

        if ($save_res['error'] == 0) {
            $this->success++;
            array_push($this->initialPreview, $this->_objectUrl($save_res->url));
            array_push($this->initialPreviewConfig, array(
                'caption' => basename($save_res->url),
                'size' => $save_res->size,
                'width' => ($save_res->width?$save_res->width:'120').'px',
                'key' => $save_res->id,
                'url' => '/admin/api/service/uploader/'.$save_res->id, // server delete action
                'extra' => array(
                    '_method' => 'delete',
                    '_token' => csrf_token(),
                ),
            ));
        }

        return $save_res;
    }

    /**
     * Get file visit url.
     *
     * @param $path
     *
     * @return string
     */
    private function _objectUrl($path)
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return rtrim(config('admin.upload.host'), '/').'/'.trim($path, '/');
    }

    /**
     * Initialize the storage instance.
     *
     * @return void
     */
    protected function _initStorage()
    {
        $this->storage = Storage::disk(config('admin.upload.disk'));
    }

    /**
     * Get store path for file upload.
     *
     * @return mixed
     */
    public function _getStorePath(UploadedFile $file = null, $default = '')
    {
        if ($file) {
            $file_type = $file->getClientMimeType();
            $type_arr = explode("/", $file_type);
            $file_dir = '';
            if (is_array($type_arr) && $type_arr) {
                switch($type_arr[0]){
                    case "image"      : $file_dir = "image"; break;
                    case "video"      : $file_dir = "video"; break;
                    case "audio"      : $file_dir = "voice"; break;
                    case "text"       : $file_dir = "doc";   break;
                    case "application": $file_dir = "doc";   break;
                    default           : $file_dir = "other"; break;
                }
            } else if ($default) {
                $file_dir = $default;
            } else {
                $file_dir = config('admin.upload.directory.default');
            }
        } else {
            $file_dir = config('admin.upload.directory.default');
        }

        return $file_dir.'/'.date('Y-m-d');
    }

    /**
     * Upload file and delete original file.
     *
     * @param UploadedFile $file
     *
     * @return object
     */
    private function _uploadAndDeleteOriginal(UploadedFile $file)
    {
        $this->_renameIfExists($file);

        $target = $this->directory.'/'.$this->filename;

        $this->storage->put($target, file_get_contents($file->getRealPath()));

        $file_obj = Upload::create(array(
            'caption' => $file->getClientOriginalName(),
            'alt' => $file->getClientOriginalName(),
            'text' => $file->getClientOriginalName(),
            'size' => $file->getClientSize(),
            'type' => $file->getClientMimeType(),
            'width' => 0,
            'height' => 0,
            'url' => $target,
            'thumb' => $target,
            'delete_url' => $target,
            'error' => $file->getError(),
        ));

        return $file_obj;
    }

    /**
     * If is delete request then delete original image.
     *
     * @return bool
     */
    private function _isDeleteRequest()
    {
        $action = Input::get($this->id.'_action');

        if ($action == static::ACTION_REMOVE) {
            $this->_destroy();

            return true;
        }

        return false;
    }

    /**
     * If name already exists, rename it.
     *
     * @param $file
     *
     * @return void
     */
    private function _renameIfExists(UploadedFile $file)
    {
        if ($this->storage->exists("$this->directory/$this->filename")) {
            $this->filename = md5(uniqid()).'.'.$file->guessExtension();
        }
    }

}