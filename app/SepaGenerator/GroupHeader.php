<?
namespace App\SepaGenerator;


class GroupHeader
{
    private $messageId;
    private $creationDateTime;
    private $numberOfTransactions;
    private $controlSum;
    private $initiatingPartyName;

    public function __construct($messageId, $initiatingPartyName, $numberOfTransactions, $controlSum)
    {
        $this->messageId = $messageId;
        $this->creationDateTime = date('Y-m-d\TH:i:s');
        $this->numberOfTransactions = $numberOfTransactions;
        $this->controlSum = number_format((float)$controlSum, 2, '.', ''); // Control sum with 2 decimals
        $this->initiatingPartyName = $initiatingPartyName;
    }

    public function toXml()
    {
        return "
        <GrpHdr>
            <MsgId>{$this->messageId}</MsgId>
            <CreDtTm>{$this->creationDateTime}</CreDtTm>
            <NbOfTxs>{$this->numberOfTransactions}</NbOfTxs>
            <CtrlSum>{$this->controlSum}</CtrlSum>
            <InitgPty>
                <Nm>{$this->initiatingPartyName}</Nm>
            </InitgPty>
        </GrpHdr>";
    }
}