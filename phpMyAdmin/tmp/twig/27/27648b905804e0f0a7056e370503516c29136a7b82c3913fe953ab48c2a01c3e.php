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

/* database/structure/copy_form.twig */
class __TwigTemplate_34287792c252f74f182f475a7fee940dd32a633f222576bb813475ee3329b359 extends \Twig\Template
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
        echo "<form id=\"ajax_form\" action=\"";
        echo PhpMyAdmin\Url::getFromRoute("/database/structure/copy-table");
        echo "\" method=\"post\">
  ";
        // line 2
        echo PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
        echo "

  <fieldset>
    <strong><label for=\"db_name_dropdown\">";
        // line 5
        echo _gettext("Database:");
        echo "</label></strong>
    <select id=\"db_name_dropdown\" name=\"target_db\">
      ";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["options"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["each_db"]) {
            // line 8
            echo "        <option value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["each_db"], "name", [], "any", false, false, false, 8), "html", null, true);
            echo "\"";
            echo ((twig_get_attribute($this->env, $this->source, $context["each_db"], "is_selected", [], "any", false, false, false, 8)) ? (" selected") : (""));
            echo ">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["each_db"], "name", [], "any", false, false, false, 8), "html", null, true);
            echo "</option>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['each_db'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 10
        echo "    </select>

    <br><br>

    <strong><label>";
        // line 14
        echo _gettext("Options:");
        echo "</label></strong>

    <br>

    <input type=\"radio\" id=\"what_structure\" value=\"structure\" name=\"what\">
    <label for=\"what_structure\">";
        // line 19
        echo _gettext("Structure only");
        echo "</label>

    <br>

    <input type=\"radio\" id=\"what_data\" value=\"data\" name=\"what\" checked>
    <label for=\"what_data\">";
        // line 24
        echo _gettext("Structure and data");
        echo "</label>

    <br>

    <input type=\"radio\" id=\"what_dataonly\" value=\"dataonly\" name=\"what\">
    <label for=\"what_dataonly\">";
        // line 29
        echo _gettext("Data only");
        echo "</label>

    <br><br>

    <input type=\"checkbox\" id=\"checkbox_drop\" value=\"true\" name=\"drop_if_exists\">
    <label for=\"checkbox_drop\">";
        // line 34
        echo _gettext("Add DROP TABLE");
        echo "</label>

    <br>

    <input type=\"checkbox\" id=\"checkbox_auto_increment_cp\" value=\"1\" name=\"sql_auto_increment\">
    <label for=\"checkbox_auto_increment_cp\">";
        // line 39
        echo _gettext("Add AUTO INCREMENT value");
        echo "</label>

    <br>

    <input type=\"checkbox\" id=\"checkbox_constraints\" value=\"1\" name=\"sql_auto_increment\" checked>
    <label for=\"checkbox_constraints\">";
        // line 44
        echo _gettext("Add constraints");
        echo "</label>

    <br><br>

    <input type=\"checkbox\" name=\"adjust_privileges\" value=\"1\" id=\"checkbox_adjust_privileges\" checked>
    <label for=\"checkbox_adjust_privileges\">
      ";
        // line 50
        echo _gettext("Adjust privileges");
        // line 51
        echo "      ";
        echo \PhpMyAdmin\Html\MySQLDocumentation::showDocumentation("faq", "faq6-39");
        echo "
    </label>
  </fieldset>
</form>
";
    }

    public function getTemplateName()
    {
        return "database/structure/copy_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 51,  133 => 50,  124 => 44,  116 => 39,  108 => 34,  100 => 29,  92 => 24,  84 => 19,  76 => 14,  70 => 10,  57 => 8,  53 => 7,  48 => 5,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/copy_form.twig", "/var/www/html/phpMyAdmin/templates/database/structure/copy_form.twig");
    }
}
