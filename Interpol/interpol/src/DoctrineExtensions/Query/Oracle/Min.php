<?php
    
namespace DoctrineExtensions\Query\Oracle;

use Doctrine\ORM\Query\Lexer,
Doctrine\ORM\Query\AST\Functions\FunctionNode;
    
class Min extends FunctionNode
{
    private $value;
    
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf(
            'MIN(%s)',
            $sqlWalker->walkArithmeticPrimary($this->value));
    }
    
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->value = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
 


?>
