<?php

namespace App\Libraries\SepaGenerator;

class SEPAService
{
    protected $messageId;
    protected $debtorName;
    protected $debtorIBAN;
    protected $debtorBIC;
    protected $groupHeader = [];
    protected $transactions = [];
    protected $debtorAddress = [];
    protected $debtorReference;  // Poziv na broj for Debtor

    public function __construct($messageId, $debtorName, $debtorIBAN, $debtorBIC, array $debtorAddress, $debtorReference)
    {
        $this->messageId = $messageId;
        $this->debtorName = $debtorName;
        $this->debtorIBAN = $debtorIBAN;
        $this->debtorBIC = $debtorBIC;
        $this->debtorAddress = $debtorAddress;
        $this->debtorReference = $debtorReference;  // Add Debtor's Poziv na broj
    }

    public function addTransaction($amount, $creditorIBAN, $creditorName, $creditorReference, $description, $creditorAddress)
    {
        $this->transactions[] = [
            'amount' => $amount,
            'creditorIBAN' => $creditorIBAN,
            'creditorName' => $creditorName,
            'pozivNaBrojPlatitelja' => $creditorReference,  // Poziv na broj for the Creditor
            'description' => $description,
            'creditorAddress' => $creditorAddress,
			
        ];
    }

public function generateXML()
{
    $xml = new \SimpleXMLElement('<Document xmlns="urn:iso:std:iso:20022:tech:xsd:scthr:pain.001.001.09"/>');
    $cstmrCdtTrfInitn = $xml->addChild('CstmrCdtTrfInitn');

    // Group Header
    $grpHdr = $cstmrCdtTrfInitn->addChild('GrpHdr');
    $grpHdr->addChild('MsgId', $this->messageId);
    $grpHdr->addChild('CreDtTm', date('Y-m-d\TH:i:s'));
    $grpHdr->addChild('NbOfTxs', count($this->transactions));
    $grpHdr->addChild('CtrlSum', array_sum(array_column($this->transactions, 'amount')));

    // Debtor Information
    $initgPty = $grpHdr->addChild('InitgPty');
    $initgPty->addChild('Nm', $this->debtorName);

    // Payment Information
    $pmtInf = $cstmrCdtTrfInitn->addChild('PmtInf');
    $pmtInf->addChild('PmtInfId', $this->messageId);
    $pmtInf->addChild('PmtMtd', 'TRF');
    $pmtInf->addChild('NbOfTxs', count($this->transactions));
    $pmtInf->addChild('CtrlSum', array_sum(array_column($this->transactions, 'amount')));
	$pmtTpInf = $pmtInf->addChild('PmtTpInf');
	$svcLvl = $pmtTpInf->addChild('SvcLvl');
	$svcLvl->addChild('Cd', 'SEPA');

    // Required Execution Date
    $reqdExctnDt = $pmtInf->addChild('ReqdExctnDt');
    $reqdExctnDt->addChild('Dt', date('Y-m-d'));

    // Debtor Details
    $dbtr = $pmtInf->addChild('Dbtr');
    $dbtr->addChild('Nm', $this->debtorName);
    $dbtrPstlAdr = $dbtr->addChild('PstlAdr');
    $dbtrPstlAdr->addChild('StrtNm', $this->debtorAddress['street']);
    $dbtrPstlAdr->addChild('BldgNb', $this->debtorAddress['buildingNumber']);
    $dbtrPstlAdr->addChild('PstCd', $this->debtorAddress['postalCode']);
    $dbtrPstlAdr->addChild('TwnNm', $this->debtorAddress['town']);
    $dbtrPstlAdr->addChild('Ctry', $this->debtorAddress['country']);

    // Debtor Account
    $dbtrAcct = $pmtInf->addChild('DbtrAcct');
    $dbtrAcct->addChild('Id')->addChild('IBAN', $this->debtorIBAN);

    // Debtor Agent (BIC)
    $dbtrAgt = $pmtInf->addChild('DbtrAgt');
    $dbtrAgt->addChild('FinInstnId')->addChild('BICFI', $this->debtorBIC);

 
        // Debtor's "Poziv na broj" (EndToEndId)

    $pmtInf->addChild('ChrgBr', 'SLEV');  // Shared charges

    // Transactions (Multiple Receivers)
    foreach ($this->transactions as $transaction) {
        $cdtTrfTxInf = $pmtInf->addChild('CdtTrfTxInf');

        // Payment ID
		$uniqueId = 'Transaction-' . uniqid();  // Generating unique EndToEndId
		$pmtId = $cdtTrfTxInf->addChild('PmtId');
       $pmtId->addChild('EndToEndId', $this->debtorReference);
        // Amount
        $amt = $cdtTrfTxInf->addChild('Amt');
        $amt->addChild('InstdAmt', number_format($transaction['amount'], 2, '.', ''))->addAttribute('Ccy', 'EUR');

        // Creditor Information
        $cdtr = $cdtTrfTxInf->addChild('Cdtr');
        $cdtr->addChild('Nm', $transaction['creditorName']);
        $cdtrPstlAdr = $cdtr->addChild('PstlAdr');
         $cdtrPstlAdr->addChild('Ctry', $transaction['creditorAddress']['country']);
       $cdtrPstlAdr->addChild('AdrLine', $transaction['creditorAddress']['adresa']);

        // Creditor Account
        $cdtrAcct = $cdtTrfTxInf->addChild('CdtrAcct');
        $cdtrAcct->addChild('Id')->addChild('IBAN', $transaction['creditorIBAN']);

        // Remittance Information (Structured with Model and Poziv na broj)
        $rmtInf = $cdtTrfTxInf->addChild('RmtInf');
        $strd = $rmtInf->addChild('Strd');
        $cdtrRefInf = $strd->addChild('CdtrRefInf');
        $tp = $cdtrRefInf->addChild('Tp');
        $cdOrPrtry = $tp->addChild('CdOrPrtry');
        $cdOrPrtry->addChild('Cd', 'SCOR'); 
        $cdtrRefInf->addChild('Ref', $transaction['pozivNaBrojPlatitelja']);
		$strd->addChild('AddtlRmtInf', $transaction['description']);
    }

    // Return the generated XML
    return $xml->asXML();
}
}
