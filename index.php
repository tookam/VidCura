<?php
/*
Plugin Name: VidCura
Description: VidCura enables you to build Roku channels without learning the Roku proprietary language. Publishing a channel is now as easy as blogging.
Version: 0.1
Plugin URI: http://www.vidcura.org/
Author: Willy Tekeu
Author URI: http://randomistas.tekeu.com/
*/

/*  
	Copyright (c) 2012 Willy Tekeu <willy.tekeu@vidcura.org>
	Portions of this distribution are copyrighted by:
		Copyright (c) 2008 Everett Griffiths <ryan@wonko.com>	
	All rights reserved.

	VidCura is distributed under the GNU General Public License, Version 2,
	June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
	St, Fifth Floor, Boston, MA 02110, USA

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
	ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
	ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*------------------------------------------------------------------------------
CONFIGURATION (for the developer): 

Define the names of functions and classes used by this plugin so we can test 
for conflicts prior to loading the plugin and message the WP admins if there are
any conflicts.

$function_names_used -- add any functions declared by this plugin in the 
	main namespace (e.g. utility functions or theme functions).

$class_names_used -- add any class names that are declared by this plugin.

Warning: the text-domain for the __() localization functions is hardcoded.
------------------------------------------------------------------------------*/
$function_names_used = array('vc_media_url', 'vc_content_type', 'vc_stream_format', 'vc_short_description', 
														 'vc_description', 'vc_sd_poster_url', 'vc_hd_poster_url');
$class_names_used = array('VidCura', 'VidCura_Category', 'VidCura_Manifest', 'VidCura_Theme');
	
// Not class constants: constants declared via define():
$constants_used = array('VIDCURA_PATH','VIDCURA_URL','VIDCURA_TXTDOMAIN');

// Used to store errors
$error_items = '';

// No point in localizing this, because we haven't loaded the textdomain yet.
function vidcura_cannot_load()
{
	global $error_items;
	print '<div id="vidcura-warning" class="error fade"><p><strong>'
	.'The VidCura plugin cannot load correctly!'
	.'</strong> '
	.'Another plugin has declared conflicting class, function, or constant names:'
	.'<ul style="margin-left:30px;">'.$error_items.'</ul>'
	.'</p>'
	.'<p>You must deactivate the plugins that are using these conflicting names.</p>'
	.'</div>';
	
}

/*------------------------------------------------------------------------------
The following code tests whether or not this plugin can be safely loaded.
If there are no conflicts, the loader.php is included and the plugin is loaded,
otherwise, an error is displayed in the manager.
------------------------------------------------------------------------------*/
// Check for conflicting function names
foreach ($function_names_used as $f_name )
{
	if ( function_exists($f_name) )
	{
		/* translators: This refers to a PHP function e.g. my_function() { ... } */
		$error_items .= sprintf('<li>%1$s: %2$s</li>', __('Function', 'vidcura'), $f_name );
	}
}
// Check for conflicting Class names
foreach ($class_names_used as $cl_name )
{
	if ( class_exists($cl_name) )
	{
		/* translators: This refers to a PHP class e.g. class MyClass { ... } */
		$error_items .= sprintf('<li>%1$s: %2$s</li>', __('Class', 'vidcura'), $f_name );
	}
}
// Check for conflicting Constants
foreach ($constants_used as $c_name )
{
	if ( defined($c_name) )
	{
		/* translators: This refers to a PHP constant as defined by the define() function */
		$error_items .= sprintf('<li>%1$s: %2$s</li>', __('Constant', 'vidcura'), $f_name );
	}
}

// Fire the error, or load the plugin.
if ($error_items)
{
	$error_items = '<ul>'.$error_items.'</ul>';
	add_action('admin_notices', 'vidcura_cannot_load');
}
// CLEARED FOR LAUNCH!!! ---> Load the plugin
else
{
	include_once('loader.php');
}

/*EOF*/