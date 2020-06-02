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
$xToCheck = $x - 1;
$yToCheck = $y - 1;

// automap
function renderMap($level, $x, $y, $d)
{
    // direction image for players position
    if ($d == 'n') {
        $char = '&#9651;';
    } elseif ($d == 'e') {
        $char = '&#9655;';
    } elseif ($d == 's') {
        $char = '&#9661;';
    } elseif ($d == 'w') {
        $char = '&#9665;';
    } else {
        $char = 'X';
    }

    // map as table
    $map = '<table>';

    // do horizontal
    foreach ($level as $keyY => $valY) {
        $map .= '<tr>';
        // do vertical
        foreach ($valY as $keyX => $valX) {
            $map .= '<td>';

            // draw walls
            if ($valX === 'w') {
                $map .= '&#11035;';
            }

            // draw char
            if ($keyY == $y - 1 && $keyX == $x - 1) {
                $map .= '<span style="font-size: 1em;">' . $char . '</span>';
            }

            $map .= '</td>';
        }

        $map .= '</tr>';
    }

    $map .= '</table>';

    return $map;
}

// control arrows
$labelFront = '&#9650;';
$labelBack = '&#9660;';
$labelLeft = '&#9700;';
$labelLeft2 = '&#9664;';
$labelRight = '&#9701;';
$labelRight2 = '&#9654;';

// moving directions - create link for each direction, if possible
if ($d == 'n') {
    $l = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=w" style="text-decoration: none;">' . $labelLeft . '</a>';
    $r = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=e" style="text-decoration: none;">' . $labelRight . '</a>';

    if (@$level[$yToCheck - 1][$xToCheck] == 'p') {
        $f = '<a href="crawl.php?x=' . $x . '&y=' . ($y - 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelFront . '</a>';
    } else {
        $f = $labelFront;
    }

    if (@$level[$yToCheck][$xToCheck + 1] == 'p') {
        $rr = '<a href="crawl.php?x=' . ($x + 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelRight2 . '</a>';
    } else {
        $rr = $labelRight2;
    }

    if (@$level[$yToCheck][$xToCheck - 1] == 'p') {
        $ll = '<a href="crawl.php?x=' . ($x - 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelLeft2 . '</a>';
    } else {
        $ll = $labelLeft2;
    }

    if (@$level[$yToCheck + 1][$xToCheck] == 'p') {
        $b = '<a href="crawl.php?x=' . $x . '&y=' . ($y + 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelBack . '</a>';
    } else {
        $b = $labelBack;
    }
} elseif ($d == 'e') {
    $l = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=n" style="text-decoration: none;">' . $labelLeft . '</a>';
    $r = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=s" style="text-decoration: none;">' . $labelRight . '</a>';

    if (@$level[$yToCheck][$xToCheck + 1] == 'p') {
        $f = '<a href="crawl.php?x=' . ($x + 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelFront . '</a>';
    } else {
        $f = $labelFront;
    }

    if (@$level[$yToCheck + 1][$xToCheck] == 'p') {
        $rr = '<a href="crawl.php?x=' . $x . '&y=' . ($y + 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelRight2 . '</a>';
    } else {
        $rr = $labelRight2;
    }

    if (@$level[$yToCheck - 1][$xToCheck] == 'p') {
        $ll = '<a href="crawl.php?x=' . $x . '&y=' . ($y - 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelLeft2 . '</a>';
    } else {
        $ll = $labelLeft2;
    }

    if (@$level[$yToCheck][$xToCheck - 1] == 'p') {
        $b = '<a href="crawl.php?x=' . ($x - 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelBack . '</a>';
    } else {
        $b = $labelBack;
    }
} elseif ($d == 's') {
    $l = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=e" style="text-decoration: none;">' . $labelLeft . '</a>';
    $r = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=w" style="text-decoration: none;">' . $labelRight . '</a>';

    if (@$level[$yToCheck + 1][$xToCheck] == 'p') {
        $f = '<a href="crawl.php?x=' . $x . '&y=' . ($y + 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelFront . '</a>';
    } else {
        $f = $labelFront;
    }

    if (@$level[$yToCheck][$xToCheck - 1] == 'p') {
        $rr = '<a href="crawl.php?x=' . ($x - 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelRight2 . '</a>';
    } else {
        $rr = $labelRight2;
    }

    if (@$level[$yToCheck][$xToCheck + 1] == 'p') {
        $ll = '<a href="crawl.php?x=' . ($x + 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelLeft2 . '</a>';
    } else {
        $ll = $labelLeft2;
    }

    if (@$level[$yToCheck - 1][$xToCheck] == 'p') {
        $b = '<a href="crawl.php?x=' . $x . '&y=' . ($y - 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelBack . '</a>';
    } else {
        $b = $labelBack;
    }
} elseif ($d == 'w') {
    $l = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=s" style="text-decoration: none;">' . $labelLeft . '</a>';
    $r = '<a href="crawl.php?x=' . $x . '&y=' . $y . '&d=n" style="text-decoration: none;">' . $labelRight . '</a>';

    if (@$level[$yToCheck][$xToCheck - 1] == 'p') {
        $f = '<a href="crawl.php?x=' . ($x - 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelFront . '</a>';
    } else {
        $f = $labelFront;
    }

    if (@$level[$yToCheck - 1][$xToCheck] == 'p') {
        $rr = '<a href="crawl.php?x=' . $x . '&y=' . ($y - 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelRight2 . '</a>';
    } else {
        $rr = $labelRight2;
    }

    if (@$level[$yToCheck + 1][$xToCheck] == 'p') {
        $ll = '<a href="crawl.php?x=' . $x . '&y=' . ($y + 1) . '&d=' . $d . '" style="text-decoration: none;">' . $labelLeft2 . '</a>';
    } else {
        $ll = $labelLeft2;
    }

    if (@$level[$yToCheck][$xToCheck + 1] == 'p') {
        $b = '<a href="crawl.php?x=' . ($x + 1) . '&y=' . $y . '&d=' . $d . '" style="text-decoration: none;">' . $labelBack . '</a>';
    } else {
        $b = $labelBack;
    }
}

// render user interface
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>dungeon crawler</title>
    <meta charset="utf-8"/>
</head>
<body>
<?php
echo '<div>' . strtoupper($d) . ' (x' . $x . ', ' . 'y' . $y . ')</div>'
    . '<p></p>'
    . '<div>' . renderMap($level, $x, $y, $d) . '</div>'
    . '<p></p>'
    . '<div><table>'
    . '<tr>'
    . '<td><span style="font-size: 2em;">' . $l . '</span></td><td>'
    . '<span style="font-size: 2em;">' . $f . '</span></td><td>'
    . '<span style="font-size: 2em;">' . $r . '</span></td>'
    . '</tr>'
    . '<tr>'
    . '<td><span style="font-size: 2em;">' . $ll . '</span></td>'
    . '<td><span style="font-size: 2em;">' . $b . '</span></td>'
    . '<td><span style="font-size: 2em;">' . $rr . '</span></td>'
    . '</tr>'
    . '</table></div>'
    . '<p></p>'
    . '<div style="width: 350px; height: 350px; background-color: black;">'
    . '<img style="width: 350px; height: 350px; border: 1px solid rgb(0, 0, 0);" src="./dungeon.php?x=' . $x . '&y=' . $y . '&d=' . $d . '"/>'
    . '</div>';
?>
</body>
</html>
