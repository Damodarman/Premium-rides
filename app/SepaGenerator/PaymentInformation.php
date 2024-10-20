<?

namespace App\SepaGenerator;

class PaymentInformation
{
    private $paymentInfoId;
    private $debtorName;
    private $debtorIBAN;
    private $debtorBIC;
    private $debtorAddress;
    private $requestedExecutionDate;
    private $transactions = [];

    public function __construct($paymentInfoId, $debtorName, $debtorIBAN, $debtorBIC, $debtorAddress)
    {
        $this->paymentInfoId = $paymentInfoId;
        $this->debtorName = $debtorName;
        $this->debtorIBAN = $debtorIBAN;
        $this->debtorBIC = $debtorBIC;
        $this->debtorAddress = $debtorAddress;
        $this->requestedExecutionDate = date('Y-m-d');
    }

    public function addTransaction($transaction)
    {
        $this->transactions[] = $transaction;
    }

    public function toXml()
    {
        $transactionsXml = implode("\n", array_map(function ($transaction) {
            return $transaction->toXml();
        }, $this->transactions));

        return "
        <PmtInf>
            <PmtInfId>{$this->paymentInfoId}</PmtInfId>
            <PmtMtd>TRF</PmtMtd>
            <NbOfTxs>" . count($this->transactions) . "</NbOfTxs>
            <CtrlSum>" . array_reduce($this->transactions, fn($sum, $txn) => $sum + $txn->getAmount(), 0) . "</CtrlSum>
            <ReqdExctnDt>{$this->requestedExecutionDate}</ReqdExctnDt>
            <Dbtr>
                <Nm>{$this->debtorName}</Nm>
                <PstlAdr>
                    <StrtNm>{$this->debtorAddress['street']}</StrtNm>
                    <BldgNb>{$this->debtorAddress['buildingNumber']}</BldgNb>
                    <PstCd>{$this->debtorAddress['postalCode']}</PstCd>
                    <TwnNm>{$this->debtorAddress['town']}</TwnNm>
                    <Ctry>{$this->debtorAddress['country']}</Ctry>
                </PstlAdr>
            </Dbtr>
            <DbtrAcct>
                <Id>
                    <IBAN>{$this->debtorIBAN}</IBAN>
                </Id>
            </DbtrAcct>
            <DbtrAgt>
                <FinInstnId>
                    <BIC>{$this->debtorBIC}</BIC>
                </FinInstnId>
            </DbtrAgt>
            {$transactionsXml}
        </PmtInf>";
    }
}