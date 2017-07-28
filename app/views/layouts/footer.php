            </div>
        </div>
    </td>
</tr>
<tr id="footer">
    <td align="center">
        <div class="uk-contrast footer">
            Автоматизированная система тестирования, <?= date ('Y') ?>
        </div>
    </td>
</tr>
</table>
<script>
    function activeBtn(page_id)
    {
        if(document.getElementById(page_id))
        {
            document.getElementById(page_id).className += ' uk-active';
        }
    }
    activeBtn('<?= $page_id ?>');
</script>

</body>
</html>