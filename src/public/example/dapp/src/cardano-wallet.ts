//@ts-nocheck
import AlpineInstance from 'alpinejs';
import { Blockfrost, Lucid, Network} from 'lucid-cardano';
// import {Buffer} from 'buffer';

declare global {
    interface Window {
        Lucid: Lucid,
        Blockfrost: Blockfrost,
        Network: Network      
    }
}

async function enableWallet(wallet: string) {
    if (typeof window.cardano === 'undefined' || !window?.cardano || !window.cardano[wallet]) {
        return Promise.reject(`${wallet} wallet not installed.`);
    }
    return window.cardano[wallet].enable();
}

export default async function getLucid(wallet: string): Promise<Lucid> {
    const api = await enableWallet(wallet);
    console.log(api)
    const networkId = await  api.getNetworkId();
    let network;
    switch (networkId) {
        case 0:
            network = 'Preview';
            break;
        default:
            console.log('getting Mainnet')
            network = 'Mainnet';
    }
    // const lucid = await Lucid.new( 
    //     new Blockfrost("https://cardano-testnet.blockfrost.io/api/v0/", "preview2QfIR5epKjaFmh54Id75yXAM7yStk3vc"),
    //     'Preprod' as Network
    // );

    // lucid.selectWallet(api);

    return 3;//lucid;
}
// Alpine.data('mike', function() {
//     return {
//         miki: null
//     }
// }).bind(Alpine)
// Show the connected wallet
// show balances (of ada and suppport assets)
// Sign payment

export async function cardanoWallet() {
    return {
        walletName: "",
        api: null,
        lucid: null,
        walletBalance: null,
        walletAddress: "",
        // stakeAddress: null,
        walletLoading: false,
        // walletService: null,
        asset: null,

        supports(wallet:string) {
            if (typeof window.cardano === 'undefined') {
                return false;
            }
            return (!!window.cardano[wallet]) && typeof window.cardano[wallet] != 'undefined';
        },       
        
        async setWalletBalance() {
            if (!this.walletName) {
                this.walletBalance = null;
                return false;
            }
            this.walletLoading = true;
            // const balance = await this.api.getBalance();
            // this.walletBalance = C.Value.from_bytes(balance);
            console.log( this.walletBalance);
            // this.walletBalance = balance.coin().to_str();

            
            // const walletBalance = C.Value.from_bytes(new Buffer.from(this.walletBalance, 'hex')).coin().to_str();
            // if (!!walletBalance) {
            //     this.walletBalance = (BigInt(walletBalance) / BigInt(1000000)).toLocaleString(undefined, {
            //         minimumFractionDigits: 2,
            //         maximumFractionDigits: 2
            //     });
            // }
            this.walletLoading = false;
            return true;
        },
        async makePayment()
        {
            // let receiverAddress, currency;
            // receiverAddress = "addr_test1qqyw9x4u9eshv2m8jc03kkdwzyr3fca76aetf7hjxa4f8nhx5cs2e7re5q4zs3dry836yr7a9cwmleymtdpt99jvsn8qpd38p6";
            if (!this.asset) {
                console.log("Yoo am not finding asset");
                return;
            }
            this.asset =  JSON.parse(this.asset);
            // if (this.asset['currency'] == "ADA") {
                // currency = 'lovelace'
                // amount = this.asset['amount'] * 1000000;
            // } else {
                // currency = this.asset['currency'];
                // amount = this.asset['amount']
            // }
            // console.log("get utxo")
        //    const tx = await this.lucid.newTx()
            // .payToAddress(receiverAddress, { currency: amount })
            // .complete();

            // console.log(tx);
        }
    }
}