<?php
/**
 * RapidCart
 *
 * An open source PHP ecommerce shopping platform
 *
 * This software is released under the GNU General Public License, version 3 (GPL-3.0)
 *
 * Copyright (c) 2017 - 2018, Mavelo, LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	RapidCart
 * @author	Robert Myllaney (https://www.robertmullaney.com/)
 * @copyright	Copyright (c) 2017 - 2018, Mavelo, LLC. (https://www.mavelo.com/)
 * @license	https://opensource.org/licenses/GPL-3.0	GPL-3.0 License
 * @link	https://www.rapidcart.com
 * @since	Version 1.0.0.0
 */

if (is_file('config.php')) {
	include 'config.php';
}

if (!defined('DIR_CATALOG')) {
	header('Location: install/index.php');
	exit;
}

require DIR_SYSTEM . 'init.php';
