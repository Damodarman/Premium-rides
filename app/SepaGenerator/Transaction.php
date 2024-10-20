<?
namespace App\SepaGenerator;

class Transaction
{
    private $amount;
    private $creditorIBAN;
    private $creditorName;
    private $creditorAddress;
    private $reference;
    private $description;

    public function __construct($amount, $creditorIBAN, $creditorName, $creditorAddress, $reference, $description)
    {
        $this->amount = number_format((float)$amount, 2, '.', '');
        $this->creditorIBAN = $creditorIBAN;
        $this->creditorName = $creditorName;
        $this->creditorAddress = $creditorAddress;
        $this->reference = $reference;
        $this->description = $description;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function toXml()
    {
        return "
        <CdtTrfTxInf>
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
