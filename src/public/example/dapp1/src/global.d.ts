import { Alpine as AlpineType } from 'alpinejs';
import {Lucid as LucidType, Blockfrost as BlockfrostType, Network as NetworkType } from 'lucid-cardano';

declare global {
    var Alpine: AlpineType,
    Lucid: LucidType,
    Blockfrost: BlockfrostType,
    Network: NetworkType  
}