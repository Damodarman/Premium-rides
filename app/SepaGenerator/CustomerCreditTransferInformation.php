<?
namespace App\SepaGenerator;

class CustomerCreditTransferInformation
{
    private $amount;
    private $creditorIBAN;
    private $creditorName;
    private $reference;
    private $description;
    private $creditorAddress;

    public function __construct($amount, $creditorIBAN, $creditorName, $reference, $description, $creditorAddress)
    {
        $this->amount = number_format((float)$amount, 2, '.', '');  // Ensure two decimal places
        $this->creditorIBAN = $creditorIBAN;
        $this->creditorName = $creditorName;
        $this->reference = $reference;
        $this->description = $description;
        $this->creditorAddress = $creditorAddress;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function toXml()
    {
        return "
        <CdtTrfTxInf>
            <PmtId>
                <InstrId>{$this->reference}</InstrId>
                <EndToEndId>{$this->reference}</EndToEndId>
            </PmtId>
            <Amt>
                <InstdAmt Ccy=\"EUR\">{$this->amount}</InstdAmt>
            </Amt>
            <Cdtr>
                <Nm>{$this->creditorName}</Nm>
                <PstlAdr>
                    <StrtNm>{$this->creditorAddress['street']}</StrtNm>
                    <BldgNb>{$this->creditorAddress['buildingNumber']}</BldgNb>
                    <PstCd>{$this->creditorAddress['postalCode']}</PstCd>
                    <TwnNm>{$this->creditorAddress['town']}</TwnNm>
                    <Ctry>{$this->creditorAddress['country']}</Ctry>
                </PstlAdr>
            </Cdtr>
            <CdtrAcct>
                <Id>
                    <IBAN>{$this->creditorIBAN}</IBAN>
                </Id>
            </CdtrAcct>
            <RmtInf>
                <Ustrd>{$this->description}</Ustrd>
            </RmtInf>
        </CdtTrfTxInf>";
    }
}