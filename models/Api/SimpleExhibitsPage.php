<?php 

class Api_SimpleExhibitsPage extends Omeka_Record_Api_AbstractRecordAdapter
{
	public function getRepresentation(Omeka_Record_AbstractRecord $record)
	{
		$representation = array(
		    'id' =>$record->id,
		    'url' => $this->getResourceUrl("/simple_exhibits/{$record->id}"),
		    'is_published'        => (bool)$record->is_published,
			'is_featured'		  => (bool)$record->is_featured,
		    'title'               => $record->title,
		    'slug'                => $record->slug,
		    'text'                => $record->text, // header text
			'content'			  => $record->content, // exhibit content
		    'updated'             => self::getDate($record->updated),
		    'inserted'            => self::getDate($record->inserted),
		    'order'               => $record->order,
		    'template'            => $record->template,
		    'use_tiny_mce'        => (bool)$record->use_tiny_mce,
		);

		if($record->modified_by_user_id){
		    $representation['modified_by_user'] = array(
		        'id'  => $record->modified_by_user_id,
		        'resource' => 'users',    
		        'url' => self::getResourceUrl("/users/{$record->modified_by_user_id}"),
		    ); 
		}else{
		    $representation['modified_by_user'] = null;
		}
		if($record->created_by_user_id){
		    $representation['created_by_user'] = array(
		        'id'  => $record->created_by_user_id,
		        'resource' => 'users',
		        'url' => self::getResourceUrl("/users/{$record->created_by_user_id}"),
		    ); 
		}else{
		    $representation['created_by_user'] = null;
		}
		if($record->parent_id){
		    $representation['parent'] = array(
		        'id'  => $record->parent_id,
		        'resource' => 'simple_exhibits',
		        'url' => self::getResourceUrl("/simple_exhibits/{$record->parent_id}"),
		    ); 
		}else{
		    $representation['parent'] = null;
		}
		if ( (string)$record->ckc_cover_image !== '' ) {//20201111 CKC
            $representation['ckc_cover_image'] = CKC_SPAGES_COVERS_URI . '/' . $record->ckc_cover_image;
        }
		return $representation;
	}
}
