<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

// Mimes
$supportedUploadedFiles = ['application/pdf'];

/**
 *
 *
 * ############## ENDPOINTS ##############
 *
 *
 */

/**
 * Welcome page
 */
Route::get('/', function () {

    // Clear for any error session stored.
    resetErrorSession();

    return view('welcome');
});

/**
 * Login page
 */
Route::get('/login', function () {

    // Show login page.
    return view('login');
});

/**
 * Sign in Process Endpoint
 */
Route::post('/signin', function (Request $request) {

    // Get credentials.
    $userName = $request->input('username');
    $password = $request->input('password');

    // Do an absurd validation. IMPROVE IT, PLEASE.
    if ($userName === 'jet-app' && $password === 'jet-app') {
        return redirect('/dashboard');
    }

    // Let the user enter to the system.
    return redirect('/login');
});

/**
 * Evidences (Dashboard) page
 */
Route::get('/dashboard', function () {

    // Get all uploaded evidences so far.
    $evidences = getEvidences();

    return view('dashboard', ['evidences' => $evidences]);
});

/**
 * Upload file process Endpoint
 */
Route::post('/upload', function (Request $request) use ($supportedUploadedFiles) {

    // Reset any error session stored.
    resetErrorSession();

    // Validate that POST request has a file in it, in case not, just let the user know.
    if ($request->hasFile('archivo') === false) {

        $request->session()->flash('error', 'Archivo no encontrado');

        return redirect('/dashboard');
    }

    // Get the uploaded file.
    $file = $request->file('archivo');

    // Validate is a supported file. If not, again, let the user know.
    if (in_array($file->getMimeType(), $supportedUploadedFiles) === false) {

        $request->session()->flash('error', 'Archivo no soportado');

        return redirect('/dashboard');

    }

    // Perform the upload by passing the file.
    uploadToOwnCloud($file);

    // Go to inform the user was a success request.
    return redirect('/success');
});

/**
 * Success page after an uploaded file
 */
Route::get('/success', function() {
    return view('success');
});


/**
 *
 *
 * ############## FUNCTIONS ##############
 *
 *
 */


/**
 * @param UploadedFile $file
 * @return mixed
 */
function uploadToOwnCloud(UploadedFile $file)
{

    // Format file name to do not break by spaces.
    $fileName = str_replace(' ', '_', $file->getClientOriginalName());

    // Move from /tmp into readable directory to be pushed into OwnCloud.
    $newFile = $file->move(__DIR__ . '/../public/tmp', $fileName);

    // Get Final path (name) file.
    $fullFileName = $newFile->getRealPath();

    // Execute CURL command.
    $curl = 'curl -X PUT -u ' . env('OWNCLOUD_USERNAME') . ':' . env('OWNCLOUD_PASSWORD') . ' "' . env('OWNCLOUD_URI') . '/' . $fileName . '" -F myfile=@"' . $fullFileName . '"';

    $results = exec($curl);

    // Return any response.
    return $results;
}

/**
 * Get Evidences array
 *
 * @return array
 */
function getEvidences()
{
    // Call CURL command to retrieve all the files within OwnCloud directory.
    $curl = 'curl -X PROPFIND -u ' . env('OWNCLOUD_USERNAME') . ':' . env('OWNCLOUD_PASSWORD') . ' "' . env('OWNCLOUD_URI') . '"';

    $results = exec($curl);

    // Start parsing CURL response from XML into an DOMDocument object.
    $document = new DOMDocument;

    // Parse XML -> DOMDocument.
    $document->validateOnParse = true;
    $document->loadXML($results);

    // Get all 'respose' elements known as 'node'.
    $object = $document->getElementsByTagName('response');

    // Will store nodes next.
    $objects = [];

    // Iterate for all the nodes retrieved to build an structured array.
    foreach ($object as $item) {

        // Break main node value to get file path and date.
        $exploded = explode(',', $item->nodeValue);

        // Format file, file_name and date.
        $file         = env('OWNCLOUD_BASEURI') . substr($exploded[0],0, -3);
        $fileExploded = explode('/', $file);
        $fileName     = $fileExploded[count($fileExploded) - 1];
        $date         = substr(ltrim($exploded[1]), 0, 20);

        // If no file name, just skip that file.
        if (empty($fileName) === true) {
            continue;
        }

        // Build structured array.
        $objects[] = [
            'file_name' => $fileName,
            'file'      => $file,
            'date'      => $date
        ];
    }

    // Return cool array.
    return $objects;
}

/**
 * Reset error session to False
 */
function resetErrorSession()
{
    session(['error' => false]);
}