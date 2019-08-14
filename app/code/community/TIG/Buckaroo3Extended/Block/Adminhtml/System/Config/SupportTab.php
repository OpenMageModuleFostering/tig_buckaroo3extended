<?php 
class TIG_Buckaroo3Extended_Block_Adminhtml_System_Config_SupportTab
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'buckaroo3extended/system/config/supportTab.phtml';

    public $buckarooSupport      = '<a href="mailto:support@buckaroo.nl">Buckaroo support</a>';
    public $anchorClose          = '</a>';
    public $totalEmail           = '<a href="mailto:info@totalinternetgroup.nl">';
    public $buckarooUrl          = '<a href="http://www.buckaroo.nl">Buckaroo</a>';
    
    protected function _prepareLayout()
    {
        //placed here, instead of in layout.xml to make sure it is only loaded for Buckaroo's section
        $this->getLayout()->getBlock('head')->addCss('css/tig_buckaroo3extended/supportTab.css');
        return parent::_prepareLayout();
    }
    
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}