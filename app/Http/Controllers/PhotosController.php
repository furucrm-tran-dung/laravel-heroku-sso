<?php

namespace App\Http\Controllers;

use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    public function showForm()
    {
        return view('photos.form');
    }

    public function submitForm(Request $request)
    {
        $client = new RekognitionClient([
            'region'    => 'us-west-2',
            'version'   => 'latest'
        ]);

        $image = fopen($request->file('photo')->getPathName(), 'r');
        $bytes = fread($image, $request->file('photo')->getSize());

        if($request->input('type') === 'nudity')
        {
            $results = $client->detectModerationLabels(['Image' => ['Bytes' => $bytes], 'MinConfidence' => intval($request->input('confidence'))]);
            dd($results);
            if(array_search('Explicit Nudity', array_column($results, 'Name')))
            {
                $message = 'This photo may contain nudity';
            }
            else
            {
                $message = 'This photo does not contain nudity';
            }
        }
        else
        {
            $results = $client->detectText(['Image' => ['Bytes' => $bytes], 'MinConfidence' => intval($request->input('confidence'))])['TextDetections'];

            $string = '';
            foreach($results as $item)
            {
                if($item['Type'] === 'LINE')
                {
                    $string .= $item['DetectedText'] . '<br/>';
                }
            }

            if(empty($string))
            {
                $message = 'This photo does not have any words';
            }
            else
            {
                $message = $string;
            }
        }

        request()->session()->flash('success', $message);

        return view('photos.form', ['results' => $results]);
    }

    public function showFormUpload()
    {
        return view('photos.form_upload');
    }

    public function submitFormUpload(Request $request)
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-northeast-1'
        ]);

        try {
            $s3->putObject([
                'Bucket' => 'furublog',
                'Key'    => 'blog_img/test1.png',
                'Body'   => fopen($request->file('photo')->getPathName(), 'r'),
                'ACL'    => 'public-read',
            ]);
        } catch (Aws\S3\Exception\S3Exception $e) {
            echo "There was an error uploading the file.\n";
        }
    }
}
