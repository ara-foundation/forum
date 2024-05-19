<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use Flarum\Extend;
use Flarum\Frontend\Document;

return [
    // Register extenders here to customize your forum!
    (new Extend\Frontend('forum'))
        ->content(function (Document $document) {
            $document->head[] = '<script>console.log("Hello, World!")</script>';
        })->js(__DIR__.'/js/dist/forum.js')
];
