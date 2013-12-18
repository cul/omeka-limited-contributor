<?php
/**
 * CsvImport_Form_Main class - represents the form on csv-import/index/index.
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 * @package CsvImport
 */

class LimitedContributorCollaborators_Form_Main extends Omeka_Form_Admin
{
    private $_columnDelimiter;
    private $_fileDelimiter;
    private $_tagDelimiter;
    private $_elementDelimiter;
    private $_fileDestinationDir;
    private $_maxFileSize;

    /**
     * Initialize the form.
     */
    public function init()
    {
        parent::init();
        
        $this->_columnDelimiter = CsvImport_RowIterator::getDefaultColumnDelimiter();
        $this->_fileDelimiter = CsvImport_ColumnMap_File::getDefaultFileDelimiter();
        $this->_tagDelimiter = CsvImport_ColumnMap_Tag::getDefaultTagDelimiter();
        $this->_elementDelimiter = CsvImport_ColumnMap_Element::getDefaultElementDelimiter();
        
        $this->setAttrib('id', 'limitedcontributor');
        $this->setMethod('post');

        $values = get_db()->getTable('ItemType')->findPairsForSelectForm();
        $values = array('' => __('Select Item Type')) + $values;
        
        $this->addElement('text', 'lc-text', array(
        		'label' => __('Users to share with'),
        		'description'=> __('These users will be able to see everything you post.'),
        		'value' => true)
        );

                
        $this->applyOmekaStyles();
        $this->setAutoApplyOmekaStyles(false);
        
        $submit = $this->createElement('submit', 
                                       'submit', 
                                       array('label' => __('Save Changes'),
                                             'class' => 'submit submit-medium'));
            
        
        $submit->setDecorators(array('ViewHelper',
                                      array('HtmlTag', 
                                            array('tag' => 'div', 
                                                  'class' => 'limitedcontributor'))));
                                            
        $this->addElement($submit);
    }


    /**
     * Validate the form post
     */
    public function isValid($post)
    {
        // Too much POST data, return with an error.
        if (empty($post) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $maxSize = $this->getMaxFileSize()->toString();
            $this->csv_file->addError(
                __('The file you have uploaded exceeds the maximum post size '
                . 'allowed by the server. Please upload a file smaller '
                . 'than %s.', $maxSize));
            return false;
        }

        return parent::isValid($post);
    }

 
}
