<h1>Управление счетом</h1>
<div>
    <h2>Здравствуйте, <?= $fio ?>!</h2>
    <a href="/Site/logout">Выйти</a>
</div>
<h3>Баланс: <span class="balance" style="color:green"><?= $balance ?></span></h3>
<div>
    <?php if ($error_message ?? '' !== ''): ?>
        <span style="color:red"><?=$error_message?></span>
    <?php endif ?>
    <form method="POST">
        <fieldset>
            <legend>Списание счета</legend>
            <label>Сумма</label>
            <input name="sum"></input>
            <button name="withdraw">Вывести</button>
        </fieldset>
    </form>
</div>
<?php if (count($transactions)): ?>
    <div>
        <fieldset>
            <legend>История транзакций</legend>
            <table width="50%">
                <tr>
                    <th align="left">Дата/Время</th>
                    <th align="left">Действие</th>
                    <th align="right">Сумма</th>
                </tr>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= $common_utils->defaultFormatDate($transaction['time']) ?></td>
                        <td><?= $transaction['action'] ?></td>
                        <td align="right"><?= $transaction['f_sum'] ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </fieldset>
    </div>
<?php endif ?>