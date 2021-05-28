  <?php
    // header('Content-type: image/jpeg');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $filename = 'f_7/pic3.jpg';

    $image_filepath = '../../uploads/thumbnails/' . $filename;
    $color = 'white';

    $subtitleText = "From the estimates, this worlds' this world expenditure." ;

    $subtitleString= wordwrap($subtitleText, 35, "\n", true);

    saveImageWithText($subtitleString, $color, $image_filepath);

    function saveImageWithText($text, $color, $source_file)
    {

        $public_file_path = '.';

        // Copy and resample the imag
        list($width, $height) = getimagesize($source_file);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($source_file);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

        // Prepare font size and colors
        $text_color = imagecolorallocate($image_p, 0, 0, 0);
        $bg_color = imagecolorallocate($image_p, 255, 255, 255);

        $font = '../../view/css/fonts/arial.ttf';
       
        $font_size = 8;
   
        // Set the offset x and y for the text position
        $offset_x = 3;
        $offset_y = 50;

        // Get the size of the text area
        $dims = imagettfbbox($font_size, 0, $font, $text);
        

        // Add text background
        imagefilledrectangle($image_p, 0, 70, 192, 98, $bg_color);

        // Add text
        imagettftext($image_p, $font_size, 0, 0, 80, $text_color, $font, $text);

        // Save the picture
        imagejpeg($image_p,  'output.jpg', 100);

        // Clear
        imagedestroy($image);
        imagedestroy($image_p);
    };
