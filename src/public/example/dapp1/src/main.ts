//@ts-nocheck
// import { cardanoWallet, getLucid } from "./cardano-wallet.ts";
// import { Blockfrost, Lucid, Network} from "https://unpkg.com/lucid-cardano@0.7.9/web/mod.js";
import { Blockfrost, Lucid, Network} from 'lucid-cardano';
import persist from '@alpinejs/persist';
import Alpine from "alpinejs";

window.Alpine = Alpine;
window.Blockfrost = Blockfrost;
window.Lucid = Lucid;
// declare global {
//   interface Window {
//       Alpine: AlpineType;      
//   }
// }

//  declare global {
//     interface Window {
//         Lucid: Lucid,
//         Blockfrost: Blockfrost,
//         Network: Network      
//     }
// }
Alpine.plugin(persist);


Alpine.data('dapp', function() {
  return {
      walletName: this.$persist(null).using(localStorage),
      lucid: null,
      walletBalance: null,
      // lovelacesBalance: null,
      // walletAddress: null,
      // stakeAddress: null,
      walletLoading: false,
      asset: null,

      async init() {      
        // 
        console.log('App started')
      },

      async enableWallet(wallet:string) {
        if (typeof window.cardano === 'undefined' || !window?.cardano || !window.cardano[wallet]) {
            return Promise.reject(`${wallet} wallet not installed.`);
        }
        return window.cardano[wallet]?.enable();
    },
      supports(wallet:string) {
        if (typeof window.cardano === 'undefined') {
            return false;
        }
        return (!!window.cardano[wallet]) && typeof window.cardano[wallet] != 'undefined';
    },
    async activateWallet(wallet: string) {
      this.walletLoading = true;
      if (typeof window.cardano === 'undefined' || !window?.cardano || !window.cardano[wallet]) {
          return Promise.reject(`${wallet} wallet not installed.`);
      }
      this.walletName = wallet;
      this.lucid = await this.getLucid(this.walletName);
      // await this.connectWallet(this.walletName);
      // await this.setWalletBalance();
      // await this.setWalletAddress();


    },
    async getLucid(wallet: string) {
      const api = await this.enableWallet(wallet);
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
      
      console.log("Setting lucid started...")
      const lucid = await Lucid.new( 
          new window.Blockfrost("https://cardano-preview.blockfrost.io/api/v0/", "previewPK9uqVKegBMrnKyeBs9NJ4Lcwl9ym5so"),
          network as Network
      );
      console.log("Lucid is set")
  
      lucid.selectWallet(api);
  
      return lucid;
  }
  }
}.bind(Alpine))
Alpine.start();
