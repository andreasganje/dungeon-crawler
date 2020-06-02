<?php
// dungeon crawler sample by Andreas Ganje (https://ganje.de)

// legend:
// w - wall
// p - passage
$level = [
    ['w', 'w', 'w', 'w', 'w', 'w'],
    ['w', 'p', 'p', 'p', 'w', 'w'],
    ['w', 'p', 'w', 'p', 'w', 'w'],
    ['w', 'p', 'p', 'p', 'p', 'w'],
    ['w', 'p', 'w', 'p', 'w', 'w'],
    ['w', 'p', 'w', 'p', 'w', 'w'],
    ['w', 'w', 'w', 'w', 'w', 'w'],
];

$x = isset($_GET['x']) ? $_GET['x'] : 2;
$y = isset($_GET['y']) ? $_GET['y'] : 2;
$d = isset($_GET['d']) ? $_GET['d'] : 's';

// -1 because of array index starting from 0
$x = $x - 1;
$y = $y - 1;

// determine current position
function getCurrentPosition($level, $y, $x, $d)
{
    // important: first draw the far images, then the nearer (overdraw)
    // middle (always at the end, to overdraw left and right)
    // consider initial placeholders (array keys) to make sure the correct order of drawing
    if ($d === 'n') {
        // players view n
        //   L M R
        // 3 . . .
        // 2 . . .
        // 1 . P .
        $layer = [
            'S4' => 'w',
            'LLL4' => isset($level[$y - 3][$x - 3]) ? $level[$y - 3][$x - 3] : 'w',
            'LL4' => isset($level[$y - 3][$x - 2]) ? $level[$y - 3][$x - 2] : 'w',
            'L4' => isset($level[$y - 3][$x - 1]) ? $level[$y - 3][$x - 1] : 'w',
            'RRR4' => isset($level[$y - 3][$x + 3]) ? $level[$y - 3][$x + 3] : 'w',
            'RR4' => isset($level[$y - 3][$x + 2]) ? $level[$y - 3][$x + 2] : 'w',
            'R4' => isset($level[$y - 3][$x + 1]) ? $level[$y - 3][$x + 1] : 'w',
            'M4' => isset($level[$y - 3][$x]) ? $level[$y - 3][$x] : 'w',
            'LL3' => isset($level[$y - 2][$x - 2]) ? $level[$y - 2][$x - 2] : 'w',
            'L3' => isset($level[$y - 2][$x - 1]) ? $level[$y - 2][$x - 1] : 'w',
            'RR3' => isset($level[$y - 2][$x + 2]) ? $level[$y - 2][$x + 2] : 'w',
            'R3' => isset($level[$y - 2][$x + 1]) ? $level[$y - 2][$x + 1] : 'w',
            'S3' => 'w',
            'M3' => isset($level[$y - 2][$x]) ? $level[$y - 2][$x] : 'w',
            'LL2' => isset($level[$y - 1][$x - 2]) ? $level[$y - 1][$x - 2] : 'w',
            'L2' => isset($level[$y - 1][$x - 1]) ? $level[$y - 1][$x - 1] : 'w',
            'RR2' => isset($level[$y - 1][$x + 2]) ? $level[$y - 1][$x + 2] : 'w',
            'R2' => isset($level[$y - 1][$x + 1]) ? $level[$y - 1][$x + 1] : 'w',
            'S2' => 'w',
            'M2' => isset($level[$y - 1][$x]) ? $level[$y - 1][$x] : 'w',
            'L1' => isset($level[$y][$x - 1]) ? $level[$y][$x - 1] : 'w',
            'R1' => isset($level[$y][$x + 1]) ? $level[$y][$x + 1] : 'w',
            'M1' => isset($level[$y][$x]) ? $level[$y][$x] : 'w',
        ];
    } elseif ($d === 'e') {
        // players view e
        //   1 2 3
        // L . . .
        // M P . .
        // R . . .
        $layer = [
            'S4' => 'w',
            'LLL4' => isset($level[$y - 3][$x + 3]) ? $level[$y - 3][$x + 3] : 'w',
            'LL4' => isset($level[$y - 2][$x + 3]) ? $level[$y - 2][$x + 3] : 'w',
            'L4' => isset($level[$y - 1][$x + 3]) ? $level[$y - 1][$x + 3] : 'w',
            'RRR4' => isset($level[$y + 3][$x + 3]) ? $level[$y + 3][$x + 3] : 'w',
            'RR4' => isset($level[$y + 2][$x + 3]) ? $level[$y + 2][$x + 3] : 'w',
            'R4' => isset($level[$y + 1][$x + 3]) ? $level[$y + 1][$x + 3] : 'w',
            'M4' => isset($level[$y][$x + 3]) ? $level[$y][$x + 3] : 'w',
            'LL3' => isset($level[$y - 2][$x + 2]) ? $level[$y - 2][$x + 2] : 'w',
            'L3' => isset($level[$y - 1][$x + 2]) ? $level[$y - 1][$x + 2] : 'w',
            'RR3' => isset($level[$y + 2][$x + 2]) ? $level[$y + 2][$x + 2] : 'w',
            'R3' => isset($level[$y + 1][$x + 2]) ? $level[$y + 1][$x + 2] : 'w',
            'S3' => 'w',
            'M3' => isset($level[$y][$x + 2]) ? $level[$y][$x + 2] : 'w',
            'LL2' => isset($level[$y - 2][$x + 1]) ? $level[$y - 2][$x + 1] : 'w',
            'L2' => isset($level[$y - 1][$x + 1]) ? $level[$y - 1][$x + 1] : 'w',
            'RR2' => isset($level[$y + 2][$x + 1]) ? $level[$y + 2][$x + 1] : 'w',
            'R2' => isset($level[$y + 1][$x + 1]) ? $level[$y + 1][$x + 1] : 'w',
            'S2' => 'w',
            'M2' => isset($level[$y][$x + 1]) ? $level[$y][$x + 1] : 'w',
            'L1' => isset($level[$y - 1][$x]) ? $level[$y - 1][$x] : 'w',
            'R1' => isset($level[$y + 1][$x]) ? $level[$y + 1][$x] : 'w',
            'M1' => isset($level[$y][$x]) ? $level[$y][$x] : 'w',
        ];
    } elseif ($d === 's') {
        // players view s
        //   R M L
        // 1 . P .
        // 2 . . .
        // 3 . . .
        $layer = [
            'S4' => 'w',
            'LLL4' => isset($level[$y + 3][$x + 3]) ? $level[$y + 3][$x + 3] : 'w',
            'LL4' => isset($level[$y + 3][$x + 2]) ? $level[$y + 3][$x + 2] : 'w',
            'L4' => isset($level[$y + 3][$x + 1]) ? $level[$y + 3][$x + 1] : 'w',
            'RRR4' => isset($level[$y + 3][$x - 3]) ? $level[$y + 3][$x - 3] : 'w',
            'RR4' => isset($level[$y + 3][$x - 2]) ? $level[$y + 3][$x - 2] : 'w',
            'R4' => isset($level[$y + 3][$x - 1]) ? $level[$y + 3][$x - 1] : 'w',
            'M4' => isset($level[$y + 3][$x]) ? $level[$y + 3][$x] : 'w',
            'LL3' => isset($level[$y + 2][$x + 2]) ? $level[$y + 2][$x + 2] : 'w',
            'L3' => isset($level[$y + 2][$x + 1]) ? $level[$y + 2][$x + 1] : 'w',
            'RR3' => isset($level[$y + 2][$x - 2]) ? $level[$y + 2][$x - 2] : 'w',
            'R3' => isset($level[$y + 2][$x - 1]) ? $level[$y + 2][$x - 1] : 'w',
            'S3' => 'w',
            'M3' => isset($level[$y + 2][$x]) ? $level[$y + 2][$x] : 'w',
            'LL2' => isset($level[$y + 1][$x + 2]) ? $level[$y + 1][$x + 2] : 'w',
            'L2' => isset($level[$y + 1][$x + 1]) ? $level[$y + 1][$x + 1] : 'w',
            'RR2' => isset($level[$y + 1][$x - 2]) ? $level[$y + 1][$x - 2] : 'w',
            'R2' => isset($level[$y + 1][$x - 1]) ? $level[$y + 1][$x - 1] : 'w',
            'S2' => 'w',
            'M2' => isset($level[$y + 1][$x]) ? $level[$y + 1][$x] : 'w',
            'L1' => isset($level[$y][$x + 1]) ? $level[$y][$x + 1] : 'w',
            'R1' => isset($level[$y][$x - 1]) ? $level[$y][$x - 1] : 'w',
            'M1' => isset($level[$y][$x]) ? $level[$y][$x] : 'w',
        ];
    } elseif ($d === 'w') {
        // players view w
        //   3 2 1
        // R . . .
        // M . . P
        // L . . .
        $layer = [
            'S4' => 'w',
            'LLL4' => isset($level[$y + 3][$x - 3]) ? $level[$y + 3][$x - 3] : 'w',
            'LL4' => isset($level[$y + 2][$x - 3]) ? $level[$y + 2][$x - 3] : 'w',
            'L4' => isset($level[$y + 1][$x - 3]) ? $level[$y + 1][$x - 3] : 'w',
            'RRR4' => isset($level[$y - 3][$x - 3]) ? $level[$y - 3][$x - 3] : 'w',
            'RR4' => isset($level[$y - 2][$x - 3]) ? $level[$y - 2][$x - 3] : 'w',
            'R4' => isset($level[$y - 1][$x - 3]) ? $level[$y - 1][$x - 3] : 'w',
            'M4' => isset($level[$y][$x - 3]) ? $level[$y][$x - 3] : 'w',
            'LL3' => isset($level[$y + 2][$x - 2]) ? $level[$y + 2][$x - 2] : 'w',
            'L3' => isset($level[$y + 1][$x - 2]) ? $level[$y + 1][$x - 2] : 'w',
            'RR3' => isset($level[$y - 2][$x - 2]) ? $level[$y - 2][$x - 2] : 'w',
            'R3' => isset($level[$y - 1][$x - 2]) ? $level[$y - 1][$x - 2] : 'w',
            'S3' => 'w',
            'M3' => isset($level[$y][$x - 2]) ? $level[$y][$x - 2] : 'w',
            'LL2' => isset($level[$y + 2][$x - 1]) ? $level[$y + 2][$x - 1] : 'w',
            'L2' => isset($level[$y + 1][$x - 1]) ? $level[$y + 1][$x - 1] : 'w',
            'RR2' => isset($level[$y - 2][$x - 1]) ? $level[$y - 2][$x - 1] : 'w',
            'R2' => isset($level[$y - 1][$x - 1]) ? $level[$y - 1][$x - 1] : 'w',
            'S2' => 'w',
            'M2' => isset($level[$y][$x - 2]) ? $level[$y][$x - 1] : 'w',
            'L1' => isset($level[$y + 1][$x]) ? $level[$y + 1][$x] : 'w',
            'R1' => isset($level[$y - 1][$x]) ? $level[$y - 1][$x] : 'w',
            'M1' => isset($level[$y][$x]) ? $level[$y][$x] : 'w',
        ];
    } else {
        $layer = [];
    }

    return $layer;
}

$canvas = imagecreatetruecolor(350, 350);
$colorLines = imagecolorallocate($canvas, 150, 150, 150);
$colorWalls = imagecolorallocate($canvas, 50, 50, 50);
$colorFloor = imagecolorallocate($canvas, 0, 0, 0);

$background = imagefilledrectangle($canvas, 0, 0, 701, 701, $colorFloor);

$layer = getCurrentPosition($level, $y, $x, $d);

// get transparency for darken the far steps
function getTransparency($canvas)
{
    return imagecolorallocatealpha(
        $canvas,
        0,
        0,
        0,
        100
    );
}

// functions for drawing walls
function drawL1W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledpolygon($canvas, [-1, 0, 0, 350, 50, 300, 50, 50], 4, $backgroundColor);
    imagepolygon($canvas, [-1, 0, 0, 350, 50, 300, 50, 50], 4, $foregroundColor);
}

function drawL2W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledpolygon($canvas, [50, 50, 50, 300, 125, 225, 125, 125], 4, $backgroundColor);
    imagepolygon($canvas, [50, 50, 50, 300, 125, 225, 125, 125], 4, $foregroundColor);

    imagefilledrectangle($canvas, -1, 50, 50, 300, $backgroundColor);
    imagerectangle($canvas, -1, 50, 50, 300, $foregroundColor);
}

function drawL2P($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 0, 50, 49, 300, getTransparency($canvas));
}

function drawLL2W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledpolygon($canvas, [-1, 117, -1, 233, 25, 225, 25, 125], 4, $backgroundColor);
    imagepolygon($canvas, [-1, 117, -1, 233, 25, 225, 25, 125], 4, $foregroundColor);
}

function drawL3W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 25, 125, 125, 225, $backgroundColor);
    imagerectangle($canvas, 25, 125, 125, 225, $foregroundColor);

    imagefilledpolygon($canvas, [125, 125, 125, 225, 150, 200, 150, 150], 4, $backgroundColor);
    imagepolygon($canvas, [125, 125, 125, 225, 150, 200, 150, 150], 4, $foregroundColor);
}

function drawL3P($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 26, 125, 124, 225, getTransparency($canvas));
}

function drawLL3W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, -1, 125, 25, 225, $backgroundColor);
    imagerectangle($canvas, -1, 125, 25, 225, $foregroundColor);

    imagefilledpolygon($canvas, [25, 125, 25, 225, 100, 200, 100, 150], 4, $backgroundColor);
    imagepolygon($canvas, [25, 125, 25, 225, 100, 200, 100, 150], 4, $foregroundColor);
}

function drawL4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 100, 150, 150, 200, $backgroundColor);
    imagerectangle($canvas, 100, 150, 150, 200, $foregroundColor);
}

function drawLL4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 50, 150, 100, 200, $backgroundColor);
    imagerectangle($canvas, 50, 150, 100, 200, $foregroundColor);
}

function drawLLL4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, -1, 150, 50, 200, $backgroundColor);
    imagerectangle($canvas, -1, 150, 50, 200, $foregroundColor);
}

function drawR1W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledpolygon($canvas, [300, 50, 300, 300, 350, 350, 350, 0], 4, $backgroundColor);
    imagepolygon($canvas, [300, 50, 300, 300, 350, 350, 350, 0], 4, $foregroundColor);
}

function drawR2W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledpolygon($canvas, [225, 125, 225, 225, 300, 300, 300, 50], 4, $backgroundColor);
    imagepolygon($canvas, [225, 125, 225, 225, 300, 300, 300, 50], 4, $foregroundColor);

    imagefilledrectangle($canvas, 300, 50, 350, 300, $backgroundColor);
    imagerectangle($canvas, 300, 50, 350, 300, $foregroundColor);
}

function drawR2P($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 301, 50, 350, 300, getTransparency($canvas));
}

function drawRR2W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledpolygon($canvas, [325, 125, 325, 225, 350, 233, 350, 117], 4, $backgroundColor);
    imagepolygon($canvas, [325, 125, 325, 225, 350, 233, 350, 117], 4, $foregroundColor);
}

function drawR3W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 225, 125, 325, 225, $backgroundColor);
    imagerectangle($canvas, 225, 125, 325, 225, $foregroundColor);

    imagefilledpolygon($canvas, [200, 150, 200, 200, 225, 225, 225, 125], 4, $backgroundColor);
    imagepolygon($canvas, [200, 150, 200, 200, 225, 225, 225, 125], 4, $foregroundColor);
}

function drawR3P($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 226, 125, 350, 225, getTransparency($canvas));
}

function drawRR3W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 325, 125, 350, 225, $backgroundColor);
    imagerectangle($canvas, 325, 125, 350, 225, $foregroundColor);

    imagefilledpolygon($canvas, [250, 150, 250, 200, 325, 225, 325, 125], 4, $backgroundColor);
    imagepolygon($canvas, [250, 150, 250, 200, 325, 225, 325, 125], 4, $foregroundColor);
}

function drawR4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 200, 150, 250, 200, $backgroundColor);
    imagerectangle($canvas, 200, 150, 250, 200, $foregroundColor);
}

function drawRR4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 250, 150, 300, 200, $backgroundColor);
    imagerectangle($canvas, 250, 150, 300, 200, $foregroundColor);
}

function drawRRR4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 300, 150, 350, 200, $backgroundColor);
    imagerectangle($canvas, 300, 150, 350, 200, $foregroundColor);
}

function drawM1W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, -1, 0, 350, 350, $backgroundColor);
    imagerectangle($canvas, -1, 0, 350, 350, $foregroundColor);
}

function drawM2W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 50, 50, 300, 300, $backgroundColor);
    imagerectangle($canvas, 50, 50, 300, 300, $foregroundColor);
}

function drawM3W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 125, 125, 225, 225, $backgroundColor);
    imagerectangle($canvas, 125, 125, 225, 225, $foregroundColor);
}

function drawM4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 150, 150, 200, 200, $backgroundColor);
    imagerectangle($canvas, 150, 150, 200, 200, $foregroundColor);
}

function drawS4W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 0, 150, 350, 200, getTransparency($canvas));
}

function drawS3W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 125, 125, 225, 225, getTransparency($canvas));
}

function drawS2W($canvas, $foregroundColor, $backgroundColor)
{
    imagefilledrectangle($canvas, 50, 50, 300, 300, getTransparency($canvas));
}

// draw image layers
foreach ($layer as $key => $value) {
    if ($value) {
        $functionName = 'draw' . $key . strtoupper($value);

        // functions are only available if there something has to be drawn
        if (function_exists($functionName)) {
            $functionName(
                $canvas,
                $colorLines,
                $colorWalls
            );
        }
    }
}

// provide image
header('Content-Type: image/jpg');

imagejpeg($canvas);
imagedestroy($canvas);
