<?php

// Simple Backend for file submission through form using
// $_FILES and displaying image properties as output. 
// $_FILES holds the file data.
/*

$fileName = $fileSize = $fileType = $tmpPath = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   // 👇 Here it checks if the file was uploaded and if there were no errors
   //uploading the file.
   if(isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === 0){
        $fileName = $_FILES['uploadedFile']['name']; // This saves the origingal file name.
        $fileType = $_FILES['uploadedFile']['type']; // This gives the file type.
        $fileSize = $_FILES['uploadedFile']['size']; // This gives the file size in bytes.
        $tmpPath= $_FILES['uploadedFile']['tmp_name']; // This is the temporary location where file stored for now.
        print_r($_FILES['uploadedFile']);
   

        echo "<br><h3>File Details</h3>";
        echo "File Name: $fileName<br>";
        echo "File Type: $fileType<br>";
        echo "File Size: " . number_format($fileSize / 1024 , 1) . " KB<br>";
        echo "Temp Location: $tmpPath<br>";
    } else{
        echo "<h3 style = color:red;>No image was uploaded</h2> ";
    }
}
*/



/*
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === 0) {

        // Max file size set to 2 megabytes
        $maxFileSize = 2 * 1024 * 1024; // 2MB = 2 * 1024KB * 1024 Bytes = 2,097,152 Bytes

        // Allowed extensions for security and validation
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

        // Original uploaded file data
        $fileName = $_FILES['uploadedFile']['name'];           // Original name of the uploaded file
        $fileSize = $_FILES['uploadedFile']['size'];           // File size in bytes
        $tmpPath  = $_FILES['uploadedFile']['tmp_name'];       // Temporary file location on the server

        // Get the file extension from original name and convert to lowercase.
        // pathinfo() breaks the filename into parts, and PATHINFO_EXTENSION()
        // gets the extension part(after the last dot).
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file extension
        if (!in_array($fileExt, $allowedExtensions)) {
            echo "<p style='color:red;'>❌ Only JPG, PNG, or PDF files are allowed.</p>";
        } elseif ($fileSize > $maxFileSize) {
            // Validate file size
            echo "<p style='color:red;'>❌ File is too large. Max 2MB allowed.</p>";
        } else{
            // This check the actual type of file beyond just reading the extension.
            $allowedMimeTypes = ["image/jpeg" ,"image/png" , "application/pdf"]; 
            $fileMime = mime_content_type($tmpPath);
            
            if(!in_array($fileMime , $allowedMimeTypes)){
                echo "<p style='color:red;'>Error:Invalid file content detected</p>";
            } else {
            // Generate a unique filename using timestamp and original base name.
            // time() gives a unique Unix timestamp (like 1718237500).
            $newFileName = time() . '_' . basename($fileName); // Avoid overwriting files
            
            // Absolute server path for the uploads folder.
            // __DIR__ gives the full path of the current folder.
            // (which is in this case uploads.php).
            // Then we append /uploads/filename.ext to get the full destination path.
            // This has given the path next step is to move the image to this path.
            $destination = __DIR__ . '/uploads/' . $newFileName; 

            // Move the uploaded file to the destination using move_uploaded_file($tmpPath, $destination).
            // This is the action that copies the file from the temporary location 
            // to your custom location (/uploads/).
            // If successful, this function returns true.
            if (move_uploaded_file($tmpPath, $destination)) {
                echo "<h3>✅ File uploaded successfully!</h3>";
                echo "Stored as: uploads/$newFileName<br>";
                echo "Size: " . round($fileSize / 1024, 2) . " KB";
            } else {
                echo "<p style='color:red;'>❌ Failed to move the uploaded file.</p>";
            }
          }
        }
    } else {
        echo "<p style='color:red;'>❌ No file was uploaded or an error occurred.</p>";
    }
}
*/






// Handling and working with multiple files. 
// Defining the name="imsges[]" and then using foreach loop and using the 
// indexing to get all the properties of same image. If there any error found
// we use continue to skip the current iteration.
/*

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxFileSize = 2 * 1024 * 1024; // 2MB
$uploadDir = __DIR__ . '/uploads/';
$messages = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_FILES['images'])) {
        $messages[] = "No files uploaded.";
    } else {
        echo "<pre>";
        print_r($_FILES['images']);
        echo "</pre>"; 
        
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $originalName = $_FILES['images']['name'][$index];
            $fileSize = $_FILES['images']['size'][$index];
            $fileType = $_FILES['images']['type'][$index];
            $error = $_FILES['images']['error'][$index];

            if ($error !== UPLOAD_ERR_OK) {
                $messages[] = "$originalName: Upload error.";
                continue;
            }

            // Validate size.
            if ($fileSize > $maxFileSize) {
                $messages[] = "$originalName: File too large.";
                continue;
            }

            // Validate extension
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                $messages[] = "$originalName: Invalid file extension.";
                continue;
            }

            // Validate MIME type using mime_content_type()
            $mime = mime_content_type($tmpName);
            if (!in_array($mime, $allowedMimeTypes)) {
                $messages[] = "$originalName: Invalid file type.";
                continue;
            }

            // Save the file
            $newName = time() . '_' . basename($originalName);
            $destination = $uploadDir . $newName;
            if (move_uploaded_file($tmpName, $destination)) {
                $messages[] = "$originalName uploaded successfully.";
            } else {
                $messages[] = "$originalName: Failed to save.";
            }
        }
    }
}
*/

$messages = [];
$allowedExtensions = ["png" , "jpg", "jpeg"];
$maxFileSize = 2 * 1024 * 1024;
$allowedMimeTypes = ["image/png", "image/jpg", "image/jpeg"];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_FILES['images'])){ 
        foreach($_FILES['images']['tmp_name'] as $index => $tmpName){
            $fileName = $_FILES['images']['name'][$index];
            $fileSize = $_FILES['images']['size'][$index];
            $fileError = $_FILES['images']['error'][$index];
            
            if($fileError == UPLOAD_ERR_OK){
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if(!in_array($fileExt, $allowedExtensions)){
                    $messages[] = "Only png , jpg and jpeg format are allowed";
                    break;
                } elseif($fileSize > $maxFileSize){
                    $messages[] = "Max file size is 2 MB";
                    break;
                } 
                $fileMimeType = mime_content_type($tmpName);
                if(!in_array($fileMimeType, $allowedMimeTypes)){
                    $messages[] = "Invalid file content detected";
                    break;
                } 
                $newFileName = time() . "_" . basename($fileName);
                $destination = __DIR__ . '/uploads/' . $newFileName;
                if(move_uploaded_file($tmpName, $destination)){
                    $messages[] = "File uploaded successfully";
                } else{
                    $messages[] = "There was an eror moving the File";
                }
                
            } else{
                $messages[] = "There was an error uploading the file";
            }


                
        }
} else{
    $messages[] = "File is not submitted";
}
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .upload-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .upload-container h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }

        .file-input-wrapper {
            position: relative;
            margin-bottom: 30px;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-display {
            border: 3px dashed #667eea;
            border-radius: 15px;
            padding: 40px 20px;
            background: linear-gradient(145deg, #f8f9ff, #e8ecff);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .file-input-display:hover {
            border-color: #764ba2;
            background: linear-gradient(145deg, #f0f4ff, #e0e8ff);
            transform: translateY(-2px);
        }

        .file-input-display.dragover {
            border-color: #764ba2;
            background: linear-gradient(145deg, #e8f0ff, #d8e8ff);
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
            display: block;
        }

        .upload-text {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }

        .upload-text strong {
            color: #667eea;
        }

        .file-info {
            margin-top: 15px;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            color: #555;
            font-size: 14px;
            display: none;
        }

        .selected-files {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.8);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            min-width: 0;
            gap: 8px;
        }

        .file-name {
            flex: 1;
            text-align: left;
            color: #333;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-right: 10px;
            min-width: 0;
        }

        .remove-file {
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .remove-file:hover {
            background: #ff3742;
        }

        .clear-all-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s ease;
        }

        .clear-all-btn:hover {
            background: #ff3742;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .messages {
            margin-top: 30px;
            text-align: left;
        }

        .messages ul {
            list-style: none;
            padding: 0;
        }

        .messages li {
            background: rgba(102, 126, 234, 0.1);
            border-left: 4px solid #667eea;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 0 8px 8px 0;
            color: #555;
            font-size: 14px;
        }

        .messages li:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 600px) {
            .upload-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .upload-container h2 {
                font-size: 24px;
            }

            .file-input-display {
                padding: 30px 15px;
            }

            .upload-icon {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Submit the file</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="file-input-wrapper">
                <input type="file" name="images[]" multiple required class="file-input" id="fileInput">
                <div class="file-input-display" id="fileInputDisplay">
                    <span class="upload-icon">📁</span>
                    <div class="upload-text">
                        <strong>Click to browse</strong> or drag and drop your files here<br>
                        <small>Multiple files supported</small>
                    </div>
                    <div class="file-info" id="fileInfo">
                        <div class="selected-files" id="selectedFiles"></div>
                        <button type="button" class="clear-all-btn" id="clearAllBtn" style="display: none;">Clear All Files</button>
                    </div>
                </div>
            </div>
            <input type="submit" value="Upload" class="submit-btn">
        </form>

        <?php
            if(!empty($messages)){
                echo '<div class="messages">';
                echo "<ul>";
                foreach($messages as $message){
                    echo "<li>" . htmlspecialchars($message) . "</li>";
                }
                echo "</ul>";
                echo '</div>';
            }
        ?>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const fileInputDisplay = document.getElementById('fileInputDisplay');
        const fileInfo = document.getElementById('fileInfo');
        const selectedFiles = document.getElementById('selectedFiles');
        const clearAllBtn = document.getElementById('clearAllBtn');
        
        let currentFiles = [];

        // Handle file input click
        fileInputDisplay.addEventListener('click', function(e) {
            // Only trigger file input if not clicking on remove buttons
            if (!e.target.classList.contains('remove-file') && !e.target.classList.contains('clear-all-btn')) {
                fileInput.click();
            }
        });

        // Handle file selection
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                currentFiles = Array.from(this.files);
                displaySelectedFiles();
            }
        });

        function displaySelectedFiles() {
            if (currentFiles.length === 0) {
                fileInfo.style.display = 'none';
                clearAllBtn.style.display = 'none';
                return;
            }

            fileInfo.style.display = 'block';
            clearAllBtn.style.display = 'inline-block';
            
            selectedFiles.innerHTML = '';
            
            currentFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                
                // Truncate filename if too long
                const displayName = file.name.length > 40 ? 
                    file.name.substring(0, 37) + '...' : 
                    file.name;
                
                fileItem.innerHTML = `
                    <span class="file-name" title="${file.name}">${displayName}</span>
                    <button type="button" class="remove-file" onclick="removeFile(${index})">×</button>
                `;
                
                selectedFiles.appendChild(fileItem);
            });
        }

        function removeFile(index) {
            currentFiles.splice(index, 1);
            updateFileInput();
            displaySelectedFiles();
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            currentFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
        }

        // Clear all files
        clearAllBtn.addEventListener('click', function() {
            currentFiles = [];
            fileInput.value = '';
            displaySelectedFiles();
        });

        // Handle drag and drop
        fileInputDisplay.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('dragover');
        });

        fileInputDisplay.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
        });

        fileInputDisplay.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
            
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
                currentFiles = files;
                updateFileInput();
                displaySelectedFiles();
            }
        });

        // Prevent default drag behaviors on document
        document.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        document.addEventListener('drop', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>