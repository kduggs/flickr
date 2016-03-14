<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use phpFlickr;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class GalleryController extends AppController
{
	/*
	 * API service for photos
	 */
	private $feedService = null;
	
	/*
	 * Items per page
	 */
	private $itemsPerPage = 50;
	
	/*
	 * Current page
	 */
	private $page = 1;
	
	/*
	 * Tags to search for ( csv )
	 */
	private $tags;
	
	public function initialize()
    {
		$this->feedService = new phpFlickr(FLICKER_API_KEY,FLICKER_SECRET);
		
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
	
	/**
	 * Rest function for gallery images
	 */
	public function index()
	{
		// set page
		$this->page = !empty( $this->request->query['page'] ) ? $this->request->query['page'] : 1;
		// Tags to search for
		$this->tags = !empty( $this->request->query['tag'] ) ? $this->request->query['tag'] : null;
		
		$result = $this->getResults();
		
		if ( !empty( $result['photos']['photo'] ) ) {
			
			foreach( $result['photos']['photo'] as &$photo ) {
				$photo['url_thumb'] = $this->feedService->buildPhotoURL($photo, 'square_150');
				$photo['url_original'] = sprintf( "https://www.flickr.com/photos/%s/%s",$photo['owner'],$photo['id']);
				$photo['url_profile'] = sprintf( "https://www.flickr.com/people/%s/",$photo['owner']);
				$photo['description'] = strip_tags( $photo['description']['_content'] );
				$photo['title'] = $photo['title'];
				$photo['tags'] = !empty( $photo['tags'] ) ? explode(' ', $photo['tags'] ) : null;
			}
		}
		
		$this->set([
            'gallery' => $result,
            '_serialize' => ['gallery']
        ]);
	}
	
	/**
	 * Get the pictures from the API source
	 * 
	 * @return type
	 */
	private function getResults()
	{
		if ( $this->tags ) {
			// Search for a certain tag and unify the array structure
			$result = array( 
				'photos' => $this->feedService->photos_search(
								array(
									'tags' => $this->tags,
									'per_page' => $this->itemsPerPage,
									'page' => $this->page,
									'extras' => 'description, owner_name, tags'
								)
				)
			);
			
		} else {
			// Get the recent photos from the feed
			$result = $this->feedService->photos_getRecent( 
				NULL, 
				'description, owner_name, tags',
				$this->itemsPerPage, 
				$this->page
			);
		}
	
		return $result;
	}
}
