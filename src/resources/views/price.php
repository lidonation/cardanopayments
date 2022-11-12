<div> 
    <h3 class="text-md text-gray-700 pl-4 flex gap-x-1.5">
        <input type="radio" value=<?php echo json_encode($payment) ?> x-model="asset">
        <span><?php echo $payment['currency']; ?></span>
        <span><?php echo $payment['amount']; ?></span>
    </h3>
</div>