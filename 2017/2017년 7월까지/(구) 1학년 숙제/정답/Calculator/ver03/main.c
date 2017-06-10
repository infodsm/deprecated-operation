#include <stdio.h>

int main() {
        int num1, num2;
        char op;

        while(1) {
                printf("숫자 두 개와 연산자를 입력하세요.(끝내려면 \"0 0 x\"을 입력하세요)\n");
                scanf("%d %d %c", &num1, &num2, &op);
                if (num1 == 0 && num2 == 0 && op == 'x') {
                        break;
                }
                switch (op) {
                case '+': printf("SUM : %d\n", num1 + num2);
                        break;
                case '-': printf("SUB : %d\n", num1 - num2);
                        break;
                case '*': printf("MUL : %d\n", num1 * num2);
                        break;
                case '/': printf("DIV : %d\n", num1 / num2);
                        break;
                }
        }
        return 0;
}
