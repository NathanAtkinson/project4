<?php 

	abstract class Validator {
		
		abstract protected function validateParam($param); 

		public function validate($value) {
			return $this->validateParam($value);
		}
	}

	class ValidationException extends Exception {
	}

	class ValidatorFactory {

		public function createValidator($type) {
			if ($type == 'email') {
				return new EmailValidator();
			} elseif ($type == 'number') {
				return new NumberValidator();
			} elseif ($type == 'phone') {
				return new PhoneNumberValidator();
			} elseif ($type == 'username') {
				return new UserNameValidator();
			} elseif ($type == 'password') {
				return new PasswordValidator();
			} elseif ($type == 'name') {
				return new NameValidator();
			} elseif ($type == 'dateofbirth') {
				return new DateOfBirthValidator();
			} elseif ($type == 'gender') {
				return new GenderValidator();
			} else {
				throw new Exception('Unknown Validator type: '. $type);
			}
		}
	}


	class UserNameValidator extends Validator {
			
		protected function validateParam($value) {
			if (preg_match('/^[A-Za-z0-9]+$/', $value) ==  0) {
				throw new ValidationException("Invalid Username: ");
			} 
		}
	}


	class EmailValidator extends Validator {

		protected function validateParam($value) {
			if (preg_match('/^[A-Za-z0-9]+@[A-Za-z0-9]+\.[A-Za-z]+$/', $value) == 0){
				throw new ValidationException('Invalid email: ');
			} else {
				return $value;
			}
		}
	}


	class PasswordValidator extends Validator {

		protected function validateParam($value) {
			if (strlen($value) < 8) {
				throw new ValidationException('Invalid Password: ');
			} else {
				return $value;
			}
		}
	}


	class PhoneNumberValidator extends Validator {

		protected function validateParam($value) {
			// 3-3-4 digits needed...that's it.  write it myself
			if (preg_match('/[0-9]{3}[ .-]?[0-9]{3}[ .-][0-9]{4}/', $value) == 0) {
				throw new ValidationException('Invalid phone number: ');
			} else {
				return $value;
			}
		}
	}


	class NumberValidator extends Validator {

		protected function validateParam($value) {
			if(!is_numeric($value)) {
				throw new ValidationException('Invalid number: ');
			} else {
				return $value;
			}
		}
	}


	class NameValidator extends Validator {

		protected function validateParam($value) {
			if(preg_match('/^[A-Za-z]+/',$value) == 0) {
				throw new ValidationException('Invalid name: ');
			} else {
				return $value;
			}
		}
	}


	class DateOfBirthValidator extends Validator {
/*how to validate a birthday month and the days? especially since some months have different
amounts of days.  What about leapyears?*/
		protected function validateParam($value) {
			if(preg_match('/([0-9]{4}-[0-9]{2}-[0-9]{2})/',$value) == 0) {
				throw new ValidationException('Invalid Date of Birth: ');
			} else {
				return $value;
			}
		}
	}


	class GenderValidator extends Validator {
		protected function validateParam($value) {
			if(preg_match('/m|f|M|F/',$value) == 0) {
				throw new ValidationException('Invalid indicator for gender: ');
			} else {
				return $value;
			}
		}
	}
?>