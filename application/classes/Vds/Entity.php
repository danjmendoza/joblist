<?php

abstract class Vds_Entity {

	/**
	 * Stores the model for this product
	 * 
	 * @var object
	 */
	protected $orm_object;

	/**
	 * Returns the unique id of the loaded product
	 * 
	 * @return integer
	 */
	public function id()
	{
		return $this->orm_object->id;
	}

	/**
	 * Saves the model to the database.
	 * 
	 * @return object
	 */
	public function save()
	{
		$this->orm_object->save();
		return $this;
	}

	public function delete()
	{
		$this->orm_object->delete();
		return $this;
	}

	/**
	 * Returns the values of the campaign as an array.
	 * 
	 * @return array
	 */
	public function as_array()
	{
		return $this->orm_object->as_array();
	}

	/**
	 * Check if the ORM object has a row loaded
	 * or if it is empty.
	 * 
	 * @return boolean
	 */
	public function loaded()
	{
		if ($this->orm_object->loaded() == TRUE)
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Acts as a setter and getter for a specifc field.
	 * 
	 * @param  string $field the name of the value to set/get
	 * @param  string $value the value to set to the fieldname
	 * @return object/string
	 */
	public function field_string($field, $value = NULL)
	{
		if ($value === NULL)
		{
			return $this->orm_object->$field;
		}
		else
		{
			$this->orm_object->$field = $value;
			return $this;
		}
	}

	/**
	 * Sets and gets an object that is related
	 * to the loaded class and has a one way link.
	 * State getting its country
	 * A city setting its county
	 * 
	 * @param  string $name   the name of the object type (country, county)
	 * @param  object $object the object that is related (instance of country, instance ofcounty)
	 * @return object 		  it either returns an instance of iteslf, or the relation
	 */
	public function field_object($name, $object = NULL)
	{
		if ($object)
		{
			$foriegn_key = $name.'_id';
			$this->orm_object->$foriegn_key = $object->id();
			return $this;
		}
		else
		{
			$class_name = ucfirst($name);
			$this->$name = $class_name::instance($this->orm_object->$name);
			
			return $this->$name;
		}
	}

}