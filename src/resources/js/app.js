window.cardanoWallet = function cardanoWallet() {
    return {
        walletName: null,
        api: null,
        lucid: null,
        walletBalance: null,
        walletAddress: null,
        stakeAddress: null,
        walletLoading: false,
        walletService: null,

        init() {

        },

        supports(wallet) {
            if (typeof window.cardano === 'undefined') {
                return false;
            }
            return (!!window.cardano[wallet]) && typeof window.cardano[wallet] != 'undefined';
        },
        async enableWallet(wallet) {
            this.walletLoading = true;
            if (typeof window.cardano === 'undefined' || !window?.cardano || !window.cardano[wallet]) {
                return Promise.reject(`${wallet} wallet not installed.`);
            }
            this.walletName = wallet;
            this.walletService = new WalletService();
            await this.walletService.connectWallet(this.walletName);
            await this.setWalletBalance();
            await this.setWalletAddress();
            this.$dispatch(
                'wallet-loaded',
                {
                    address: this.walletAddress,
                    stakeAddress: this.stakeAddress,
                    name: this.walletName,
                    balance: this.walletBalance
                }
            );
            this.walletLoading = false;
        },
        async setWalletAddress()
        {
            this.walletAddress = await this.walletService.getAddress();
            this.stakeAddress = await this.walletService.getStakeAddress();
        },
        async setWalletBalance() {
            if (!this.walletName) {
                this.walletBalance = null;
                return false;
            }
            this.walletLoading = true;
            this.walletBalance = await this.walletService.getBalance(this.walletName);
            const walletBalance = C.Value.from_bytes(Buffer.from(this.walletBalance, 'hex')).coin().to_str();
            if (!!walletBalance) {
                this.walletBalance = (BigInt(walletBalance) / BigInt(1000000)).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            this.walletLoading = false;
            return true;
        },
        async payToAddress(address, assets)
        {
            return await this.walletService.payToAddress(address, assets);
        }
    }
}
