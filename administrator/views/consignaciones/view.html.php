<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Servin2
 * @author     Aldo Ulises <aldouli6@gmail.com>
 * @copyright  2018 Aldo Ulises
 * @license    Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Servin2.
 *
 * @since  1.6
 */
class Servin2ViewConsignaciones extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		Servin2Helper::addSubmenu('consignaciones');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = Servin2Helper::getActions();

		JToolBarHelper::title(JText::_('COM_SERVIN2_TITLE_CONSIGNACIONES'), 'consignaciones.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/consignacion';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('consignacion.add', 'JTOOLBAR_NEW');

				if (isset($this->items[0]))
				{
					JToolbarHelper::custom('consignaciones.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
				}
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('consignacion.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('consignaciones.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('consignaciones.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'consignaciones.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('consignaciones.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('consignaciones.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'consignaciones.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('consignaciones.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_servin2');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_servin2&view=consignaciones');
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`no_folio_pagare`' => JText::_('COM_SERVIN2_CONSIGNACIONES_NO_FOLIO_PAGARE'),
			'a.`tipo_transaccion`' => JText::_('COM_SERVIN2_CONSIGNACIONES_TIPO_TRANSACCION'),
			'a.`pieza`' => JText::_('COM_SERVIN2_CONSIGNACIONES_PIEZA'),
			'a.`cantidad`' => JText::_('COM_SERVIN2_CONSIGNACIONES_CANTIDAD'),
			'a.`cliente`' => JText::_('COM_SERVIN2_CONSIGNACIONES_CLIENTE'),
			'a.`proveedor`' => JText::_('COM_SERVIN2_CONSIGNACIONES_PROVEEDOR'),
			'a.`total`' => JText::_('COM_SERVIN2_CONSIGNACIONES_TOTAL'),
			'a.`abono`' => JText::_('COM_SERVIN2_CONSIGNACIONES_ABONO'),
			'a.`adeudo`' => JText::_('COM_SERVIN2_CONSIGNACIONES_ADEUDO'),
			'a.`fecha_emision`' => JText::_('COM_SERVIN2_CONSIGNACIONES_FECHA_EMISION'),
			'a.`fecha_limite`' => JText::_('COM_SERVIN2_CONSIGNACIONES_FECHA_LIMITE'),
			'a.`devolucion`' => JText::_('COM_SERVIN2_CONSIGNACIONES_DEVOLUCION'),
			'a.`fecha_devolucion`' => JText::_('COM_SERVIN2_CONSIGNACIONES_FECHA_DEVOLUCION'),
		);
	}

    /**
     * Check if state is set
     *
     * @param   mixed  $state  State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }
}
