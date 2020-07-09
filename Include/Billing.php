<?php
	require "../Connection.php";
	require "Server.php";
	
	class Billing {
		// Variables
		private $iConnection;
		
		// Constructor
		// @params void
		// @return void
		public function __construct( ) {
			$this->iConnection = DatabaseConnection::Create( );
		}
		
		// Destructor
		// @params void
		// @return void
		public function __destruct( ) {
			unset( $this );
		}
		
		// Method for getting user bills
		// @params:
		// $iUser - Integer - User id
		// @teturn - Array - User bills
		public function GetBills( $iUser ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `Billing` WHERE `Owner` = '" . $iUser . "' ORDERED BY `Id` ASC;" ) );
		}
		
		// Method for getting bill info
		// @params:
		// $iBill - Integer - Bill id
		// @teturn - Array - Bill info
		public function GetBill( $iBill ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT * FROM `Billing` WHERE `Id` = '" . $iBill . "';" ) );
		}
		
		// Method for setting bill status
		// @params
		// $iBill - Integer - Bill id
		// $iStatus - Integer - ( 0 - Invalid | 1 - Payed/Valid | 2 - In review | 3 - Needs to be reviewed)
		// @return void
		public function SetBillStatus( $iBill, $iStatus ) {
			$this->iConnection->ExecuteQuery( "UPDATE `Bills` SET `Status` = '" .$iStatus . "' WHERE `Id` = '" . $iBill . "';" );
		}
		
		// Method for adding funds to user account
		// @parms:
		// $iUser - Integer - User id
		// $iMoney - Integer - Money
		// $szCustomerName - String - Customer name
		// $szDate - String - Date of payment
		// $szBankAcount - String - Number of bank account
		// $szCountry - String - County
		// $szComment - String - URL(s) of image(s)
		// $aServers - Array - Servers
		public function AddFunds( $iUser, $iMoney, $szCustomerName, $szDate, $szBankAccount, $szCountry, $szComment, $aServers ) {
			$szServers = "";
			foreach( $aServers as $szValue ) {
				$szServers .= $szValue . "<br  />";
			}
			
			$this->Connection->ExecuteQuery( "INSERT INTO `Billing` ( `Owner`, `Value`, `Customer`, `PaymentDate`, `AddedDate`, `BankAccount`, `Country`, `Comments`, `Servers` ) VALUES( " . $iUser . ", " . $iMoney . ", '" . $szCustomer . "', '" . $szDate . "', '" . date( "%d.%m.%Y %H:%i", time( ) ) . "', '" . $szBankAccount . "', '" . $szCountry . "', '" . $szComment . "', '" . $szServers . "' );" );
		}
		
		// Method for getting user money
		// @params:
		// $iUser - Integer - User ID
		// @return Integer - User money
		public function GetMoney( $iUser ) {
			return $this->iConnection->FetchField( $this->iConnection->ExecuteQuery( "SELECT * FROM `Clients` WHERE `Id` = '" . $iUser . "';" ), "Money" );
		}
		// Method for deleting bill
		public function DeleteBill( $iBill ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `Billing` WHERE `Id` = '" . $iBill . "';" );
		}
		// Method for setting user money
		// @params:
		// $iUser - Integer - User ID
		// $iMoney - Integer - Money
		// @return void
		public function SetMoney(	$iUser, $iMoney ) {
			$this->iConnection->ExecuteQuery( "UPDATE `Clients` SET `Money` = '" . $iMoney . "' WHERE `Id` = '" . $iUser . "';" );
		}
		
		// Functon for paying server
		// @params:
		// $iServer - Integer - Server Id
		// $iUser - Integer - User Id
		// $iMonths - Integer - Number of months
		// @return - Integer - ( 0 - Fail | 1 - Success | 2 - Free server)
		// TO DO: Add discount for multiple  months payment
		public function PayServer( $iServer, $iUser, $iMonths = 1 ) {
			// Variables
			$aServerData = new Server( $iServer )->GetData( );
			$iPrice = $aServerData[ "Price" ];
			$iMoney = $this->GetMoney( $iUser );
			
			// Check if server is free
			if( $iPrice == 0 ) {
				return 2;
			}
			
			// TO DO: 
			// $iDiscount = $iPrice - ( $iMonths 
			
			// User does not have money
			if( $iPrice > $iMoney ) {
				return 0;
		} 
		// Decrease user money and extend server
		else {
			$this->iConnection->ExecuteQuery( "UPDATE `Clients` SET `Money` = '" . $iMoney - $iPrice . "' WHERE `Id` = '" . $iUser . "';" );
			
			// Set new expiration date for server
			$iSecoundsInMonth = ( 60 * 60 * 24 ) * ( date( '%m' ) + $iMonths ); // 60 secounds * 60 minutes * 24 hours * days in secified number of next months
			$szNewExpiration = date( '%d.%m.Y', time( ) + $iSecoundsInMonth ); // Format date from unix timestamp of this day + number of secounds in next month
			$this->iConnection->ExecuteQuery( "UPDATE `Servers` SET `Expiration` = '" . $szNewExpiration  . "' WHERE `Id` = '" . $iServer . "';" );
			return;
		 }
		}
	}
 ?>