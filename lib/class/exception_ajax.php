<?php
 /**
 * Copyright (C) 2010 by Ger Hobbelt (hebbut.net)
 * 
 * @package CompactCMS.nl
 * @license GNU General Public License v3
 * 
 * This file is part of CompactCMS.
 * 
 * CompactCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CompactCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * A reference to the original author of CompactCMS and its copyright
 * should be clearly visible AT ALL TIMES for the user of the back-
 * end. You are NOT allowed to remove any references to the original
 * author, communicating the product to be your own, without written
 * permission of the original copyright owner.
 * 
 * You should have received a copy of the GNU General Public License
 * along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
 * 
 * > Contact: google and you got me.
**/


/*
 * And, no, I don't care much for PHP4. Good riddance.
 *                                                         Ger
 */

/**
 * A custom AJAX-oriented 'error feedback' Exception class which performs the error reporting in a standardized way for CCMS:
 *
 * 'status' URL query parameter will be set to 'error'.
 * 'msg' URL query parameter will contain the rawurlencode()d error message to be displayed on screen user-side.
 *
 * @note You must set up the 'Location:' header destination URL through the (static) SetFeedbackLocation() method 
 *       before ANY INSTANCE OF THIS CLASS my be throw()n: the setting is 'global' for all thrown instances, so
 *       they'll all direct to the designated location until the next time you call SetFeedbackLocation().
 *
 *       The intended use is like this:
 *
 * <pre>
 * // start of run due to AJAX request incoming
 * CcmsAjaxFbException::SetFeedbackLocation($cfg['rootdir'] . "admin/modules/template-editor/backend.php");
 *
 * try
 * {
 *   ...
 *
 *   if (some_operation() == failed) 
 *     throw new CcmsAjaxFbException("urgh! we failed dramtically!");
 *   ...
 *   header('Location: ' . makeAbsoluteURI($cfg['rootdir'] . 'admin/modules/template-editor/backend.php&status=success&msg=hunky+dory!'));
 *   exit();
 * }
 * catch (CcmsAjaxFbException $e)
 * {
 *   $e->croak(); // our equivalent of die() 
 * }
 * </pre>
 */
class CcmsAjaxFbException extends Exception
{
	protected static $feedback_url = null;
	protected $extra_url_query_data = null;

	public static function SetFeedbackLocation($location)
	{
		self::$feedback_url = $location;
	}
	
    // Redefine the exception so message isn't optional
    public function __construct($message, $more_url_query_data = null, $code = 0, Exception $previous = null) 
	{
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
		
		$this->extra_url_query_data = $more_url_query_data;
    }

    //public function __toString() 
	//{
    //    return $this->message;
    //}

    public function croak() 
	{
		if (!empty(self::$feedback_url))
		{
			header('Location: ' . makeAbsoluteURI(self::$feedback_url . '?status=error&msg='.rawurlencode($this->message) . $this->extra_url_query_data));
			exit();
		}
		// if we get here, this exception class hasn't been set up according to requirements. Barf a hairball.
		throw new Exception(__CLASS__ . ": feedback URL missing - a programmer error. INTERNAL ERROR. Happened when reporting the nested exception.", 666, $this);
    }
}


?>