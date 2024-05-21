<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* indexes.twig */
class __TwigTemplate_550e90a77f67e3673c647adda1f502e2e88839374d6808c36a4da3e1a1406b5c extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<fieldset class=\"index_info\">
  <legend id=\"index_header\">
    ";
        // line 3
        echo _gettext("Indexes");
        // line 4
        echo "    ";
        echo \PhpMyAdmin\Html\MySQLDocumentation::show("optimizing-database-structure");
        echo "
  </legend>

  ";
        // line 7
        if ( !twig_test_empty(($context["indexes"] ?? null))) {
            // line 8
            echo "    ";
            echo ($context["indexes_duplicates"] ?? null);
            echo "

    <div class=\"table-responsive jsresponsive\">
      <table class=\"table table-light table-striped table-hover table-sm w-auto\" id=\"table_index\">
        <thead class=\"thead-light\">
        <tr>
            <th colspan=\"3\" class=\"print_ignore\">";
            // line 14
            echo _gettext("Action");
            echo "</th>
            <th>";
            // line 15
            echo _gettext("Keyname");
            echo "</th>
            <th>";
            // line 16
            echo _gettext("Type");
            echo "</th>
            <th>";
            // line 17
            echo _gettext("Unique");
            echo "</th>
            <th>";
            // line 18
            echo _gettext("Packed");
            echo "</th>
            <th>";
            // line 19
            echo _gettext("Column");
            echo "</th>
            <th>";
            // line 20
            echo _gettext("Cardinality");
            echo "</th>
            <th>";
            // line 21
            echo _gettext("Collation");
            echo "</th>
            <th>";
            // line 22
            echo _gettext("Null");
            echo "</th>
            <th>";
            // line 23
            echo _gettext("Comment");
            echo "</th>
          </tr>
        </thead>

        ";
            // line 27
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["indexes"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["index"]) {
                // line 28
                echo "          <tbody class=\"row_span\">
            ";
                // line 29
                $context["columns_count"] = twig_get_attribute($this->env, $this->source, $context["index"], "getColumnCount", [], "method", false, false, false, 29);
                // line 30
                echo "            <tr class=\"noclick\">
              <td rowspan=\"";
                // line 31
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\" class=\"edit_index print_ignore ajax\">
                <a class=\"ajax\" href=\"";
                // line 32
                echo PhpMyAdmin\Url::getFromRoute("/table/indexes");
                echo "\" data-post=\"";
                echo PhpMyAdmin\Url::getCommon(twig_array_merge(($context["url_params"] ?? null), ["index" => twig_get_attribute($this->env, $this->source, $context["index"], "getName", [], "method", false, false, false, 32)]), "");
                echo "\">
                  ";
                // line 33
                echo \PhpMyAdmin\Html\Generator::getIcon("b_edit", _gettext("Edit"));
                echo "
                </a>
              </td>
              <td rowspan=\"";
                // line 36
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\" class=\"rename_index print_ignore ajax\" >
                <a class=\"ajax\" href=\"";
                // line 37
                echo PhpMyAdmin\Url::getFromRoute("/table/indexes/rename");
                echo "\" data-post=\"";
                echo PhpMyAdmin\Url::getCommon(twig_array_merge(($context["url_params"] ?? null), ["index" => twig_get_attribute($this->env, $this->source, $context["index"], "getName", [], "method", false, false, false, 37)]), "");
                echo "\">
                  ";
                // line 38
                echo \PhpMyAdmin\Html\Generator::getIcon("b_rename", _gettext("Rename"));
                echo "
                </a>
              </td>
              <td rowspan=\"";
                // line 41
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\" class=\"print_ignore\">
                ";
                // line 42
                if ((twig_get_attribute($this->env, $this->source, $context["index"], "getName", [], "method", false, false, false, 42) == "PRIMARY")) {
                    // line 43
                    echo "                  ";
                    $context["index_params"] = ["sql_query" => (("ALTER TABLE " . PhpMyAdmin\Util::backquote(                    // line 44
($context["table"] ?? null))) . " DROP PRIMARY KEY;"), "message_to_show" => _gettext("The primary key has been dropped.")];
                    // line 47
                    echo "                ";
                } else {
                    // line 48
                    echo "                  ";
                    $context["index_params"] = ["sql_query" => (((("ALTER TABLE " . PhpMyAdmin\Util::backquote(                    // line 49
($context["table"] ?? null))) . " DROP INDEX ") . PhpMyAdmin\Util::backquote(twig_get_attribute($this->env, $this->source, $context["index"], "getName", [], "method", false, false, false, 49))) . ";"), "message_to_show" => sprintf(_gettext("Index %s has been dropped."), twig_get_attribute($this->env, $this->source,                     // line 50
$context["index"], "getName", [], "method", false, false, false, 50))];
                    // line 52
                    echo "                ";
                }
                // line 53
                echo "
                <input type=\"hidden\" class=\"drop_primary_key_index_msg\" value=\"";
                // line 54
                echo PhpMyAdmin\Sanitize::jsFormat(twig_get_attribute($this->env, $this->source, ($context["index_params"] ?? null), "sql_query", [], "any", false, false, false, 54), false);
                echo "\">
                ";
                // line 55
                echo PhpMyAdmin\Html\Generator::linkOrButton(PhpMyAdmin\Url::getFromRoute("/sql", twig_array_merge(                // line 56
($context["url_params"] ?? null), ($context["index_params"] ?? null))), \PhpMyAdmin\Html\Generator::getIcon("b_drop", _gettext("Drop")), ["class" => "drop_primary_key_index_anchor ajax"]);
                // line 59
                echo "
              </td>
              <th rowspan=\"";
                // line 61
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["index"], "getName", [], "method", false, false, false, 61), "html", null, true);
                echo "</th>
              <td rowspan=\"";
                // line 62
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, $context["index"], "getType", [], "method", true, true, false, 62)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, $context["index"], "getType", [], "method", false, false, false, 62), twig_get_attribute($this->env, $this->source, $context["index"], "getChoice", [], "method", false, false, false, 62))) : (twig_get_attribute($this->env, $this->source, $context["index"], "getChoice", [], "method", false, false, false, 62))), "html", null, true);
                echo "</td>
              <td rowspan=\"";
                // line 63
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, $context["index"], "isUnique", [], "method", false, false, false, 63)) ? (_gettext("Yes")) : (_gettext("No"))), "html", null, true);
                echo "</td>
              <td rowspan=\"";
                // line 64
                echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["index"], "isPacked", [], "method", false, false, false, 64);
                echo "</td>

              ";
                // line 66
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["index"], "getColumns", [], "method", false, false, false, 66));
                foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                    // line 67
                    echo "                ";
                    if ((twig_get_attribute($this->env, $this->source, $context["column"], "getSeqInIndex", [], "method", false, false, false, 67) > 1)) {
                        // line 68
                        echo "                  <tr class=\"noclick\">
                ";
                    }
                    // line 70
                    echo "                <td>
                  ";
                    // line 71
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["column"], "getName", [], "method", false, false, false, 71), "html", null, true);
                    echo "
                  ";
                    // line 72
                    if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, $context["column"], "getSubPart", [], "method", false, false, false, 72))) {
                        // line 73
                        echo "                    (";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["column"], "getSubPart", [], "method", false, false, false, 73), "html", null, true);
                        echo ")
                  ";
                    }
                    // line 75
                    echo "                </td>
                <td>";
                    // line 76
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["column"], "getCardinality", [], "method", false, false, false, 76), "html", null, true);
                    echo "</td>
                <td>";
                    // line 77
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["column"], "getCollation", [], "method", false, false, false, 77), "html", null, true);
                    echo "</td>
                <td>";
                    // line 78
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["column"], "getNull", [0 => true], "method", false, false, false, 78), "html", null, true);
                    echo "</td>

                ";
                    // line 80
                    if ((twig_get_attribute($this->env, $this->source, $context["column"], "getSeqInIndex", [], "method", false, false, false, 80) == 1)) {
                        // line 81
                        echo "                  <td rowspan=\"";
                        echo twig_escape_filter($this->env, ($context["columns_count"] ?? null), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["index"], "getComments", [], "method", false, false, false, 81), "html", null, true);
                        echo "</td>
                ";
                    }
                    // line 83
                    echo "            </tr>
              ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 85
                echo "          </tbody>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['index'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 87
            echo "      </table>
    </div>
  ";
        } else {
            // line 90
            echo "    <div class=\"no_indexes_defined\">";
            echo call_user_func_array($this->env->getFilter('notice')->getCallable(), [_gettext("No index defined!")]);
            echo "</div>
  ";
        }
        // line 92
        echo "</fieldset>
";
    }

    public function getTemplateName()
    {
        return "indexes.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  286 => 92,  280 => 90,  275 => 87,  268 => 85,  261 => 83,  253 => 81,  251 => 80,  246 => 78,  242 => 77,  238 => 76,  235 => 75,  229 => 73,  227 => 72,  223 => 71,  220 => 70,  216 => 68,  213 => 67,  209 => 66,  202 => 64,  196 => 63,  190 => 62,  184 => 61,  180 => 59,  178 => 56,  177 => 55,  173 => 54,  170 => 53,  167 => 52,  165 => 50,  164 => 49,  162 => 48,  159 => 47,  157 => 44,  155 => 43,  153 => 42,  149 => 41,  143 => 38,  137 => 37,  133 => 36,  127 => 33,  121 => 32,  117 => 31,  114 => 30,  112 => 29,  109 => 28,  105 => 27,  98 => 23,  94 => 22,  90 => 21,  86 => 20,  82 => 19,  78 => 18,  74 => 17,  70 => 16,  66 => 15,  62 => 14,  52 => 8,  50 => 7,  43 => 4,  41 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "indexes.twig", "/var/www/html/phpMyAdmin/templates/indexes.twig");
    }
}
