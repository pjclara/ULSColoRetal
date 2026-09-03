import { Option } from '@/types/type';
import { Check, ChevronsUpDown, X } from 'lucide-react';
import { useMemo, useState } from 'react';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';

interface AppMultiSelectProps {
    label?: string;
    value: string[];
    options?: Option[];
    onChange: (value: string[]) => void;
    placeholder?: string;
    searchPlaceholder?: string;
    emptyMessage?: string;
    error?: string;
    disabled?: boolean;
}

export default function AppMultiSelect({
    label,
    value = [],
    options = [],
    onChange,
    placeholder = 'Selecionar...',
    searchPlaceholder = 'Pesquisar...',
    emptyMessage = 'Nenhum resultado encontrado.',
    error,
    disabled = false,
}: AppMultiSelectProps) {
    const [open, setOpen] = useState(false);

    const selectedOptions = useMemo(
        () => options.filter((option) => value.includes(String(option.value))),
        [options, value],
    );

    const toggleOption = (optionValue: string) => {
        const isSelected = value.includes(optionValue);

        if (isSelected) {
            onChange(value.filter((item) => item !== optionValue));
        } else {
            onChange([...value, optionValue]);
        }
    };

    const removeOption = (optionValue: string) => {
        onChange(value.filter((item) => item !== optionValue));
    };

    return (
        <div className="space-y-2">
            {label && (
                <label className="text-sm font-medium">
                    {label}
                </label>
            )}

            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button
                        type="button"
                        variant="outline"
                        disabled={disabled}
                        className={cn(
                            'min-h-10 w-full justify-between',
                            !selectedOptions.length && 'text-muted-foreground',
                            error && 'border-destructive',
                        )}
                    >
                        <div className="flex flex-1 flex-wrap gap-1 text-left">
                            {selectedOptions.length > 0 ? (
                                selectedOptions.map((option) => (
                                    <Badge
                                        key={option.value}
                                        variant="secondary"
                                        className="gap-1"
                                    >
                                        {option.label}

                                        <span
                                            role="button"
                                            tabIndex={0}
                                            className="cursor-pointer rounded-full hover:bg-muted"
                                            onClick={(event) => {
                                                event.stopPropagation();
                                                removeOption(
                                                    String(option.value),
                                                );
                                            }}
                                            onKeyDown={(event) => {
                                                if (
                                                    event.key === 'Enter' ||
                                                    event.key === ' '
                                                ) {
                                                    event.preventDefault();
                                                    event.stopPropagation();
                                                    removeOption(
                                                        String(option.value),
                                                    );
                                                }
                                            }}
                                        >
                                            <X className="h-3 w-3" />
                                        </span>
                                    </Badge>
                                ))
                            ) : (
                                <span>{placeholder}</span>
                            )}
                        </div>

                        <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                    </Button>
                </PopoverTrigger>

                <PopoverContent
                    className="w-[var(--radix-popover-trigger-width)] p-0"
                    align="start"
                >
                    <Command>
                        <CommandInput placeholder={searchPlaceholder} />

                        <CommandList>
                            <CommandEmpty>
                                {emptyMessage}
                            </CommandEmpty>

                            <CommandGroup>
                                {options.map((option) => {
                                    const optionValue = String(option.value);
                                    const isSelected =
                                        value.includes(optionValue);

                                    return (
                                        <CommandItem
                                            key={optionValue}
                                            value={`${option.label} ${optionValue}`}
                                            onSelect={() =>
                                                toggleOption(optionValue)
                                            }
                                        >
                                            <Check
                                                className={cn(
                                                    'mr-2 h-4 w-4',
                                                    isSelected
                                                        ? 'opacity-100'
                                                        : 'opacity-0',
                                                )}
                                            />

                                            {option.label}
                                        </CommandItem>
                                    );
                                })}
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </PopoverContent>
            </Popover>

            {error && (
                <p className="text-sm text-destructive">
                    {error}
                </p>
            )}
        </div>
    );
}