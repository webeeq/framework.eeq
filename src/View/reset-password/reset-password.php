                <h2>Resetowanie</h2>
                <p>Opcja ta pozwala na zresetowanie hasła do systemu. Proszę podać login do swojego konta.</p>
                <form method="post">
<?php
echo $array['error'];
?>
                    <table>
                        <tr>
                            <td>Login:</td>
                            <td><input type="text" name="login" value="<?php echo stripslashes($array['login']); ?>" size="20" maxlength="20" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" name="submit" value="Zatwierdź" /></td>
                        </tr>
                    </table>
                    <input type="hidden" name="token" value="<?php echo $array['token']; ?>" />
                </form>
