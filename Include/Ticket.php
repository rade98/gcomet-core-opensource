<?php
	require "./Connection.php";

	class Ticket {
		private $iConnection;

		public function __construtc( ) {
			$iConnection = DataBaseConnection::Create( );
		}

		public function __destruct( ) {
			unset( $this );
		}

		public function GetTickets( $iUser ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT *, DATE_FORMAT( `Date`, \"%d.%m.%Y\") AS `Date` FROM `Tickets` WHERE `Owner` = '" . $iUser . "' ORDER BY `Id` DESC;") );
		}

		public function GetAnswers( $iTicket ) {
			return $this->iConnection->FetchArray( $this->iConnection->ExecuteQuery( "SELECT *, DATE_FORMAT( `Date`, \"%d.%m.%Y %H:%i\") AS `Date` FROM `TicketAnswers` WHERE `Id` = '" . $iTicket . "' ORDER BY `Id` ASC;") );
		}

		public function CreateTicket( $iUser, $szTitle, $szContent, $iServer, $iPriority ) {
			$this->iConnection->ExecuteQuery( "INSERT INTO `Tickets` ( `Owner`, `Title`, `Content`, `Date`, `Server`, `Priority` ) VALUES( " . $iUser . ", '" . $szTitle . "', '" . stripslashes( htmlspecialchars( $szContent ) ) . "', '" . date( "%d.%m.%Y", time( ) ) . "', ". $iServer . ", " . $iPriority . " );" );
		}

		public function CreateAnswer( $iTicket, $szTitle, $szContent, $iUser ) {
			$this->iConnection->ExecuteQuery( "INSERT INTO `TicketAnswers` ( `Ticket`, `Title`, `Content`, `Date`, `User` ) VALUES( " . $iTicket . ", '" . $szTitle . "', '" . stripslashes( htmlspecialchars( $szContent ) ) . "', '" . date( "%d.%m.%Y", time( ) ) . "', " . $iUser . " );" );
		}

		public function CloseTicket( $iTicket ) {
			$this->iConnection->ExecuteQuery( "UPDATE `Tickets` SET `Status` = '0' WHERE `Id` = '" . $iTicket . "';" );
		}

		public function DeleteTicket( $iTicket ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `TicketAnswers` WHERE `Id` = '" . $iTicket . "';DELETE FROM `Ticket` WHERE `Id` = '" . $iTicket . "';" );
		}

		public function DeleteAnswer( $iId ) {
			$this->iConnection->ExecuteQuery( "DELETE FROM `TicketAnswers` WHERE `Id` = '" . $iId . "';" );
		}

		public function GetTicketStatus( $iTicket ) {
			return $this->iConnection->FetchField( $iConnection->ExecuteQuery( "SELECT * FROM `Tickets` WHERE `Id`" . $iTicket ."';" ), "Status" );
		}
	}
?>