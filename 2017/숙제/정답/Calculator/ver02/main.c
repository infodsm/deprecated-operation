#include <stdio.h>

int main() {
        int num1, num2;

        while(1) {
                printf("숫자 두 개를 입력하세요.(끝내려면 \"0 0\"을 입력하세요)\n");
                scanf("%d %d", &num1, &num2);
                if (num1 == 0 && num2 == 0) {
                        break;
                }
                printf("SUM : %d\n", num1 + num2);
        }
        return 0;
}
